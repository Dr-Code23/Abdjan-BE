<?php

namespace App\Http\Resources;

use App\Http\Resources\Translations\ProductTranslationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource =  [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'name' => $this->name,
            'description' => $this->description,
        ];

        foreach(['attribute' , 'unit' , 'category' , 'brand'] as $relation){
            if($this->relationLoaded($relation)){
                $resource[$relation."_name"] = $this->{$relation}->name;
            }
        }
        return $resource;
    }


}
