<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;;
use \Webpatser\Countries\Countries;
use App\Acme\Helpers\ApiHelper;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $countries = new Countries();      
        return ApiHelper::parseMultiple($countries, ['name'], $request->all());
    }
}