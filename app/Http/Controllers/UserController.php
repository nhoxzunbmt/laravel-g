<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return User::all();
    }
    
    public function show($id)
    {
        return User::findOrFail($id);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        
        if($request->has('q')) 
        {
            $items = User::search($request->get('q'))->orderBy('id', 'asc')->paginate(12);
            
            if($items->count()==0)
                return response()->json($error);

            return response()->json($items);
        }
        
        return response()->json($error);
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
    
    /**
	 * Update the specified resource in storage.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
	public function update(UserUpdateRequest $request, $id = false)
	{
        $data = [];
        $input = $request->except(['avatar', 'overlay']);
        if(!$id)
        {
            $user = JWTAuth::parseToken()->authenticate();
            $id = $user->id;
        }else{
            $user = User::findOrFail($id);
        }
        
        if ($request->has('password')) 
            $user->password = bcrypt($request->password);
        
        if($result = $user->update($input))
        {
            $data = User::getApiUserData($user);
        }
        
        return response()->json($data);
	}
    
    /**
	 * Update avatar.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
	public function avatar(Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();
        if($user->avatar)
        {
            Storage::delete($user->avatar);
        }
            
        $path = Storage::disk('public')->putFile(
            'avatars', $request->file('files')
        );
        $user->avatar = $path;
        $user->update();
        $data = User::getApiUserData($user);
        
        return response()->json($data);
	}
    
    /**
	 * Update overlay.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
	public function overlay(Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();
        if($user->overlay)
        {
            Storage::delete($user->overlay);
        }
            
        $path = Storage::disk('public')->putFile(
            'avatars', $request->file('files')
        );
        $user->overlay = $path;
        $user->update();
        $data = User::getApiUserData($user);
        
        return response()->json($data);
	}
}