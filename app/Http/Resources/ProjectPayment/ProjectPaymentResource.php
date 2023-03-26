<?php

namespace App\Http\Resources\ProjectPayment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'customer_name' => $this->customer_name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'project_price' => round($this->total , 2),
            'paid_before' => round($this->paid_money , 2),
            'remaining_money' => round($this->total - $this->paid_money , 2),
            'project_payments' => DerivedProjectPaymentResource::collection(
                $this->whenLoaded('project_payments')
            )
        ];
    }
}
