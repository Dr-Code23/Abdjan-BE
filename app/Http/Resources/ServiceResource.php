<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ServiceResource extends JsonResource
{

    public function __construct(
        $resource ,
        private readonly array|int|null $fullyTranslated = null,
        private readonly bool           $showServiceDetails = false,
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
        $images = [];

        if($this->relationLoaded('images')){
            foreach($this->images as $image){
                $images[] = $image->original_url;
            }

            if(!$images){
                for($i = 0 ; $i<4 ; $i++){
                    $images[] = asset('/storage/default/service.png');
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->fullyTranslated['name'] ?? $this->name,
            'description' => $this->when(
                $this->showServiceDetails || isPublicRoute(),
                $this->fullyTranslated['description'] ?? $this->description
            ),
            $this->mergeWhen($this->relationLoaded('category') , function(){
                return [
                    'category_id' => $this->when(isNotPublicRoute(),$this->category->id),
                ];
            }),
            'status' => $this->when(
                !$this->showServiceDetails && isNotPublicRoute() ,
                (bool) $this->status
            ),
            'price' => round($this->price , 2),
            'phone' => $this->when($this->showServiceDetails , $this->phone),
            'images' => $this->when($images != [] , $images),
            'created_at' => $this->when(
                $this->showServiceDetails && isNotPublicRoute() ,
                $this->created_at
            ),
            'updated_at' => $this->when(
                $this->showServiceDetails  && isNotPublicRoute(),
                $this->updated_at
            ),
        ];
    }
}
