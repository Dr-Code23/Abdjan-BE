<?php

namespace App\Http\Resources\ProjectExpense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project_expense_id' => $this->id,
            'created_at' => $this->created_at,
            'project_expense_products' => ProjectExpenseProductResource::collection(
                $this->whenLoaded('project_expense_product')
            )
        ];
    }
}
