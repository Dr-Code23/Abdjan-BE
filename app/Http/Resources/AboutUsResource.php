<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutUsResource extends JsonResource
{

    private array $fullyTranslatedContent = [];
    public function __construct($resource, array $fullyTranslatedContent = [])
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
            'name' => $this->fullyTranslatedContent['name'] ?? $this->name,
            'description' => $this->fullyTranslatedContent['description'] ?? $this->description,
            $this->mergeWhen($this->relationLoaded('image') , function(){
                return [
                    'image' => $this->image->first()->original_url ?? asset('/storage/default/category.png')
                ];
            })
        ];
    }
}
