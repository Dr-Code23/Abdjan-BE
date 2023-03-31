<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    protected array|int $fullyTranslatedContent;
    public function __construct($resource , array|int $fullyTranslatedContent = [])
    {
        parent::__construct($resource);

        $this->fullyTranslatedContent = $fullyTranslatedContent;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->fullyTranslatedContent['title'] ?? $this->title,
            'description' => $this->fullyTranslatedContent['description'] ?? $this->description,
            'discount' => round($this->discount),
            $this->mergeWhen($this->relationLoaded('image') , function(){
                return [
                    'image' => $this->image->first()->original_url ?? asset('/storage/default/category.png')
                ];
            })
        ];
    }
}
