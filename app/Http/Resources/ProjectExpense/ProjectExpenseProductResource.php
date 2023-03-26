<?php

namespace App\Http\Resources\ProjectExpense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectExpenseProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project_expense_product_id' => $this->id,
            $this->mergeWhen($this->relationLoaded('product') , function(){
                return [
                    'product_name' => $this->product->name,
                ];
            }),
            'quantity' => $this->quantity,

        ];
    }
}
