<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->mergeWhen($this->relationLoaded('product') , function(){
               return [
                   'id' => $this->product->id,
                   'name' =>$this->product->name,
               ];
            }),
            'quantity' => $this->quantity,
        ];
    }
}
