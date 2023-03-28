<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role_id' => $this->role_id,
            $this->mergeWhen($this->relationLoaded('role') , function(){
                return [
                    'role_name' => $this->role->name
                ];
            }),
            'avatar' => asset('storage/users/'.($this->avatar ?:'user.png')),
        ];
    }
}
