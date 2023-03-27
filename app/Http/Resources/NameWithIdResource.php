<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NameWithIdResource extends JsonResource
{

    private array|int|null $translatedName;
    public function __construct($resource , array|int|null $translatedName = null)
    {
        $this->translatedName = $translatedName;
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => is_array($this->translatedName) ? $this->translatedName : $this->name,
        ];
    }
}
