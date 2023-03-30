<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    protected array $fullyTranslatedContent;
    public function __construct($resource , array $fullyTranslatedContent = [])
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
            'phones' => $this->phones ?? [],
            'social_links' => $this->social_links ?? [],
            'address' => $this->address,
            $this->mergeWhen($this->relationLoaded('logo') , function(){
                return [
                    'logo' => $this->logo->first()->original_url ?? asset('/storage/default/store.png')
                ];
            })
        ];
    }
}
