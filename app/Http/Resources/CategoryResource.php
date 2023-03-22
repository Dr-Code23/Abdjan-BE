<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sub_categories_count' => $this->whenHas('sub_categories_count'),
            'sub_categories' => NameWithIdResource::collection(
                $this->whenLoaded('sub_categories')
            ),
        ];
    }
}
