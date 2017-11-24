<?php

namespace App\Acme\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use ApiHandler;

class ApiHelper{
    
    public static function parseSingle($queryBuilder, $identification, $queryParams)
    {
        $path = Route::getFacadeRoot()->current()->uri();
        $path = str_replace("api/", "", $path);
        
        return ApiHandler::parseSingle($queryBuilder, $identification, $queryParams)->cleanup(true)->getResponse();
    }
    
    public static function parseMultiple($queryBuilder, $fullTextSearchColumns, $queryParams)
    {
        $path = Route::getFacadeRoot()->current()->uri();
        $path = str_replace("api/", "", $path);
        
        if(isset($queryParams["page"]) && intval($queryParams["page"])>0 || (isset($queryParams["_limit"]) && intval($queryParams["_limit"])>0))
        {
            unset($queryParams["page"]);
            if(isset($queryParams["_limit"]) && intval($queryParams["_limit"])>0)
            {
                $limit = intval($queryParams["_limit"]);
            }else{
                $limit = 10;
            }
            return ApiHandler::parseMultiple($queryBuilder, $fullTextSearchColumns, $queryParams)->getBuilder()->paginate($limit);//->getBuilder()->toSql();
        }else{
            return ApiHandler::parseMultiple($queryBuilder, $fullTextSearchColumns, $queryParams)->cleanup(true)->getResponse();
        }
    }
}