<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
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
        $avatar = null;

        if($this->relationLoaded('avatar')){
            $avatar = $this->avatar->first()->original_url ?? null;

            if(!$avatar){
                $avatar =  asset('storage/default/user.png');
            }
        }

        return [
            'id' => $this->id,
            'email' => $this->email,
            $this->mergeWhen($this->relationLoaded('roles') , function(){
                return [
                    'role_id' => $this->roles->first()->id,
                    'role_name' => $this->roles->first()->name,
                ];
            }),
            'avatar' => $this->whenNotNull($avatar),
        ];
    }
}
