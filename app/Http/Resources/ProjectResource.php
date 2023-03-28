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
        $profits = $this->project_total - $this->total;
        if($profits < 0) {
            $profits = 0;
        }

        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'project_name' => $this->project_name,
            'project_total' => round($this->project_total , 2),
            'materials_total' => round($this->total , 2),
            'profits' => round($profits,2),
            'start_date' => $this->whenNotNull($this->start_date),
            'end_date' => $this->whenNotNull($this->end_date),
            'materials' => MaterialResource::collection($this->whenLoaded('materials'))
        ];
    }
}
