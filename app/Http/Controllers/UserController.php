<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
    
    public function show($id)
    {
        return User::findOrFail($id);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$user = JWTAuth::parseToken()->authenticate();
        //$userId = $user->id;
        return response()->json($request->all());
    }
}