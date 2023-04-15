<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    protected string $type;

    public function __construct($resource , string $type)
    {
        $this->type = $type;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [];

        if($this->type == 'project_expense'){
            return [
                $this->mergeWhen($this->relationLoaded('project_expenses') , function(){
                    return [
                        'id' => $this->project_expenses->first()->id,
                        'project_name' => $this->project_name,
                        'customer_name' => $this->customer_name,
                        'created_at' => $this->project_expenses->first()->created_at,
                        $this->mergeWhen($this->whenLoaded('project_expense_product') , function(){
                            $projectExpenseProducts = $this->project_expenses->first()->project_expense_product;

                            $products = [];
                            $i = 0;
                            foreach($projectExpenseProducts as $product){
                                $products[$i]['id'] = $product->product->id;
                                $products[$i]['name'] = $product->product->name;
                                $products[$i]['quantity'] = $product->quantity;
                                $products[$i]['unit_price'] = $product->price_per_unit;
                                $products[$i]['sub_total'] = $products[$i]['quantity'] * $products[$i]['unit_price'];
                                $products[$i]['description'] = $product->product->description;
                                $i++;
                            }

                            return['products' => $products];
                        })
                    ];
                })
            ];
        }

        return parent::toArray($request);
    }
}
