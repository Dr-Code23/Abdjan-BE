<?php

namespace App\Http\Controllers;

class SearchController extends Controller
{
    public function searchForHandle($query , array $searchableKeys , $handle){

        if(!is_null($handle)){
            $isFirstKey = false;
            foreach($searchableKeys as $key){
                if(!$isFirstKey){
                    $query->where($key , $handle);
                    $isFirstKey = true;
                } else {
                    $query->orWhere($key , $handle);
                }
            }
        }
    }
}
