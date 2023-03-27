<?php

namespace App\Http\Resources;

use App\Http\Resources\Translations\ProductTranslationResource;
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
        $optionalImages = [];
        foreach($this->optional_images ?? [] as $image){
            $optionalImages[] = asset('/storage/products/' . $image);
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
            'main_image' => $this->when($this->isShow,$this->main_image ? asset('/storage/products/' . $this->main_image) : null),
            'optional_images' => $this->when($this->isShow,$optionalImages)

        ];

        if($this->isShow){
            foreach(['attribute' , 'unit' , 'category' , 'brand'] as $relation){
                if($this->relationLoaded($relation)){
                    $resource[$relation."_name"] = $this->{$relation}->name;
                }
            }
        }
        return $resource;
    }


}
