<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;;
use \Webpatser\Countries\Countries;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $countries = Countries::orderBy('name', 'asc')->select('id', 'name', 'flag')->get()->toArray();
        return response()->json($countries, 200);
    }
}