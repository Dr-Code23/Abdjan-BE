<?php

namespace App\Http\Controllers;

class SearchController extends Controller
{
    public function searchForHandle($query , array $searchableKeys , $handle , array $translatedKeys = []){

        if(!is_null($handle)){
            $isFirstKey = false;
            foreach($searchableKeys as $key){
                if(in_array($key , $translatedKeys)){
                    foreach(config('translatable.locales') as $locale){
                        if(!$isFirstKey){
                            $query->where("$key->$locale" , 'like' , "%$handle%");
                            $isFirstKey = true;
                        } else {
                            $query->orWhere("$key->$locale" , 'like' , "%$handle%");
                        }
                    }
                }

                else {
                    if (!$isFirstKey) {
                        $query->where($key, $handle);
                        $isFirstKey = true;
                    } else {
                        $query->orWhere($key, $handle);
                    }
                }
            }
        }
    }
}
