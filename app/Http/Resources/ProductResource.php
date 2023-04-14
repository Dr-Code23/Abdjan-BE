<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function __construct(
                                        $resource ,
        private readonly array|int|null $fullyTranslated = null,
        private readonly bool           $showProductDetails = false,
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

            if(!$images){
                for($i = 0 ; $i<4 ; $i++){
                    $images[] = asset('/storage/default/product.webp');
                }
            }
        }

        $resource =  [
            'id' => $this->id,
            'name' => $this->fullyTranslated['name'] ?? $this->name,
            'description' => $this->when(
                $this->showProductDetails || isPublicRoute(),
                $this->fullyTranslated['description'] ?? $this->description
            ),
            'quantity' =>  $this->when($this->showProductDetails || isPublicRoute(),$this->quantity),
            'unit_price' => round($this->unit_price , 2),
            'status' => $this->when(
                !$this->showProductDetails && isNotPublicRoute() ,
                (bool)$this->status
            ),
            'created_at' => $this->when($this->showProductDetails,$this->created_at),
            'updated_at' => $this->when($this->showProductDetails , $this->updated_at),
            'images' => $this->when($images != [] , $images)
        ];

        if($this->showProductDetails){
            foreach(['attribute' , 'unit' , 'category' , 'brand'] as $relation){
                if($this->relationLoaded($relation)){
                    if(isNotPublicRoute()) {
                        $resource[$relation . "_id"] = $this->{$relation}->id;
                    }
                    if(isPublicRoute()){
                        $resource[$relation . "_name"] = $this->{$relation}->name;
                    }
                }
            }
        }

        return $resource;
    }
}
