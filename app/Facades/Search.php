<?php

namespace App\Facades;

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void searchForHandle($query , array $searchableKeys , $handle , array $translatedKeys = []) This Method Handle Searching For All Models
 * @see SearchController
*/
class Search extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SearchController::class;
    }
}
