<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Models\Team;
use App\Models\TeamUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        try{
            $statusCode = 200;
            $response = User::filter($request->all())->with(['fights', 'country', 'teams'])->active()->orderBy('balance', 'desc')->paginate(12);                    
        }catch (Exception $e){
            $statusCode = 404;
        }        
        return response()->json($response, $statusCode);
    }
    
    public function show($id)
    {
        $response = [];
	    try{
            $statusCode = 200;
            $user = User::find($id);
            $response = App\User::getApiUserData($user);
        }catch (Exception $e){
            $statusCode = 404;
        }
        
        return response()->json($response, $statusCode);
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
            $items = User::search($request->get('q'))->orderBy('id', 'asc');
            
            if($request->has('type')) 
            {
                $items = $items->whereType($request->get('type'));
            }
            
            if($request->has('active')) 
            {
                $items = $items->active();
            }
            
            $items = $items->paginate(12);
            
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
	public function update($id = false, UserUpdateRequest $request)
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
            
        if($user->confirmed)
        {
            $user->active = 1;
        }
        
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
        $params = $request->all();
        $user = JWTAuth::parseToken()->authenticate();
        
        if($user->avatar)
        {
            $path = public_path() . '/storage/' . $user->avatar;
            if(file_exists($path)) 
            {
                unlink($path);
            }
        }
            
        $path = Storage::disk('public')->putFile(
            'avatars', $request->file('files')
        );
        
        /**
         * Crop & resize using client crop data
         */
        $img = Image::make('storage/'.$path);
        $img->crop((int)$params["toCropImgH"], (int)$params["toCropImgW"], (int)$params["toCropImgX"], (int)$params["toCropImgY"]);
        $img->resize(150, 150);
        $img->save('storage/'.$path);
        $img->destroy();
        
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
            $path = public_path() . '/storage/' . $user->overlay;
            if(file_exists($path)) 
            {
                unlink($path);
            }
        }
            
        $path = Storage::disk('public')->putFile(
            'avatars', $request->file('files')
        );
        $user->overlay = $path;
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
	public function teams($id, Request $request)
	{
        $items = User::find($id)->teams()
            ->where("team_user.status", 1)
            ->with(['users', 'game'])
            ->paginate(12)
            ->appends('page');

        return response()->json($items);
	}
    
    
    /**
	 * Get invitation list to teams.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
    public function invitesToTeam($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        $items = $user->teams()->with(['game'])
            ->where("team_user.status", 0)
            ->where("team_user.sender_id", "<>", $user->id)
            ->get();
        
        return response()->json($items, 200);
    }
}