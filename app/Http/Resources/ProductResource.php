<?php

namespace App\Http\Resources;

use App\Http\Resources\Translations\ProductTranslationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function __construct($resource , private readonly array|int|null $fullTranslated = null)
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
        foreach($this->optional_images as $image){
            $optionalImages[] = asset('/storage/products/' . $image);
        }
        $resource =  [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'name' => $this->fullTranslated['name'] ??$this->name,
            'description' => $this->fullTranslated['description'] ?? $this->description,
            'main_image' => $this->main_image ? asset('/storage/products/' . $this->main_image) : null,
            'optional_images' => $optionalImages

        ];

        foreach(['attribute' , 'unit' , 'category' , 'brand'] as $relation){
            if($this->relationLoaded($relation)){
                $resource[$relation."_name"] = $this->{$relation}->name;
            }
        }
        return $resource;
    }


}
