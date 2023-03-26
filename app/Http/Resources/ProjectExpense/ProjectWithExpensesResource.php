<?php

namespace App\Http\Resources\ProjectExpense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectWithExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project_id' => $this->id,
            'project_name' => $this->project_name,
            'customer_name' => $this->customer_name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total' => $this->total,
            'project_expenses' => ProjectExpenseResource::collection($this->whenLoaded('project_expenses')),
        ];
    }
}
