<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicProjectResource extends JsonResource
{
    public function __construct($resource, private readonly bool $includeId = true)
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
            'id' => $this->when($this->includeId , $this->id),
            'project_name' => $this->project_name,
            'customer_name' => $this->customer_name,
        ];
    }
}
