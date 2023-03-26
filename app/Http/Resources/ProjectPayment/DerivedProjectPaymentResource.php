<?php

namespace App\Http\Resources\ProjectPayment;

use App\Traits\DateTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DerivedProjectPaymentResource extends JsonResource
{
    use DateTrait;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'created_at' => $this->created_at,
        ];
    }
}
