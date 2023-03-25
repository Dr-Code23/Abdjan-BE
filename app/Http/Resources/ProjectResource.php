<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'customer_name' => $this->customer_name,
            'project_name' => $this->project_name,
            'total' => $this->whenNotNull($this->total),
            'start_date' => $this->whenNotNull($this->start_date),
            'end_date' => $this->whenNotNull($this->end_date),
            'materials' => MaterialResource::collection($this->whenLoaded('materials'))
        ];
    }
}
