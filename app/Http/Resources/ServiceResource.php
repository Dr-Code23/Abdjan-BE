<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'price' => $this->price,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if($this->relationLoaded('translation')){
            $resource['translation'] =  new TranslationResource($this->translation);
        }if($this->relationLoaded('translations')){
        $resource['translations'] =  TranslationResource::collection($this->translations);
    }
        return $resource;
    }
}
