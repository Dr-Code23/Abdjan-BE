<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProjectWithPaymentService
{
    /**
     * @param array $data
     * @return Model|Builder|array|null
     */
    public function store(array $data): Model|Builder|array|null
    {
        $errors = [];

        //TODO Check if the project exists
        $project = Project::where('id', $data['project_id'])
            ->first(['id', 'total', 'end_date']);

        if ($project) {
            if ($project->end_date > date('Y-m-d')) {
                //TODO check if total sum is less than or equal project price to accept payment
                $allPayments = ProjectPayment::where('project_id', $data['project_id'])->sum('price');

                if (($allPayments + $data['price']) <= $project->total) {
                    ProjectPayment::create([
                        'project_id' => $data['project_id'],
                        'price' => $data['price']
                    ]);

                    return $this->show($project->id);

                } else {
                    $errors['price'] = translateErrorMessage('total_price', 'project.price');
                }
            } else {
                $errors['project'] = translateErrorMessage('project', 'project.completed');
            }
        } else {
            $errors['project'] = translateErrorMessage('project', 'not_found');
        }

        return $errors;
    }

    /**
     * @param int $project
     * @return Model|Builder|null
     */
    public function show(int $project): Model|Builder|null
    {
        $projectWithPayments = Project::with('project_payments')
            ->where('id', $project)
            ->first();

        if ($projectWithPayments) {
            $paidMoney = 0;
            foreach ($projectWithPayments->project_payments as $payment) {
                $paidMoney += $payment->price;
            }
            $projectWithPayments->paid_money = $paidMoney;

            return $projectWithPayments;
        }

        return null;
    }

    /**
     * @param array $data
     * @param int $projectPayment
     * @return Model|Builder|array|null
     */
    public function update(array $data, int $projectPayment): Model|Builder|array|null
    {
        $errors = [];
        $project = Project::where('id', $data['project_id'])
            ->first(['id', 'end_date']);

        //TODO Check If the Project Exists , and check if it's completed or not
        if ($project) {
            if ($project['end_date'] > date('Y-m-d')) {

                $projectPayment = ProjectPayment::with('project')
                    ->where('id', $projectPayment)
                    ->first();

                if ($projectPayment) {

                    $allPaymentsSum = ProjectPayment::where('project_id', $projectPayment->project_id)
                        ->where('id', '<>', $projectPayment->id)
                        ->sum('price');

                    if ($projectPayment->project->total >= ($allPaymentsSum + $data['price'])) {
                        $projectPayment->update($data);

                        return $this->show($projectPayment->project_id);

                    } else {
                        $errors['price'] = translateErrorMessage('total_price', 'project.price');
                    }
                } else {
                    $errors['project_payment'] = translateErrorMessage('project_payment', 'not_found');
                }
            } else {
                $errors['project'] = translateErrorMessage('project', 'project.completed');
            }

        } else {
            $errors['project'] = translateErrorMessage('project', 'not_found');
        }

        return $errors;
    }
}
