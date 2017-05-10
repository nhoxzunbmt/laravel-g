<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

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
}