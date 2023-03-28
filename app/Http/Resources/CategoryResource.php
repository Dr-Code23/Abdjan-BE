<?php

namespace App\Http\Resources;

use App\Interfaces\HasStatusColumn;
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
        $image = null;

        if($this->relationLoaded('images')){
            $image = $this->media->first()->original_url ?? null;
            $image = $image ?: asset('/storage/default/category.png');
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sub_categories_count' => $this->whenHas('sub_categories_count'),
            'status' => $this->when(
                $this->parent_id == null && isNotPublicRoute(),
                (bool)$this->status
            ),
            'img' => $this->whenNotNull($image),
            'sub_categories' => NameWithIdResource::collection(
                $this->whenLoaded('sub_categories')
            ),
        ];
    }
}
