<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function __construct(
        $resource ,
        private readonly array|int|null $fullTranslated = null
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
        return [
            'id' => $this->id,
            'name' => $this->fullTranslated['name'] ?? $this->name,
            'img' => $this->img ? asset('/storage/brands/' . $this->img) : null,
        ];
    }
}
