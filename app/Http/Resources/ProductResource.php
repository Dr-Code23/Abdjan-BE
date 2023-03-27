<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function __construct(
                                        $resource ,
        private readonly array|int|null $fullTranslated = null,
        private readonly bool           $isShow = false,
    )
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $images = [];

        if($this->relationLoaded('images')){
            foreach($this->images as $image){
                $images[] = $image->original_url;
            }
        }
        $resource =  [
            'id' => $this->id,
            'name' => $this->fullTranslated['name'] ?? $this->name,
            'description' => $this->when($this->isShow,$this->description),
            'quantity' =>  $this->when($this->isShow,$this->quantity),
            'unit_price' => round($this->unit_price , 2),
            'status' => $this->when(
                !$this->isShow ,
                (bool)$this->status
            ),
            'created_at' => $this->when($this->isShow,$this->created_at),
            'updated_at' => $this->when($this->isShow , $this->updated_at),
            'media' => $images

        ];

        if(!$this->isShow){
            foreach(['attribute' , 'unit' , 'category' , 'brand'] as $relation){
                if($this->relationLoaded($relation)){
                    $resource[$relation."_name"] = $this->{$relation}->name;
                }
            }
        }
        //return parent::toArray($request);
        return $resource;
    }


}
