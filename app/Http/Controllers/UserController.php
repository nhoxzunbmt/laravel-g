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
use App\Models\Fight;
use ApiHandler;
use App\Acme\Helpers\ApiHelper;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */     
    public function index(Request $request)
    {
        /*$response = [];
        try{
            $statusCode = 200;
            $response = User::filter($request->all())->with(['fights', 'country', 'team'])->active()->orderBy('balance', 'desc')->paginate(12);                    
        }catch (Exception $e){
            $statusCode = 404;
        }        
        return response()->json($response, $statusCode);*/
        $users = new User();
        return ApiHelper::parseMultiple($users, ['name', 'last_name'], $request->all());
    }
    
    public function show($id, Request $request)
    {
        $user = new User();
        return ApiHelper::parseSingle($user, $id, $request->all());
    }
    
    /**
     * Me with meta token
     */
    public function me(Request $request)
    {
        //$data = User::getApiUserData($request->user());
        $user = $request->user();
        if($user->avatar)
            $user->avatar = asset('storage/'.$user->avatar);
        if($user->overlay)
            $user->overlay = asset('storage/'.$user->overlay);
        
        return response()->json($user, 200);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function search(Request $request)
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
    }*/
    
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
        /*if(!$id)
        {
            $user = JWTAuth::parseToken()->authenticate();
            $id = $user->id;
        }else{
            $user = User::findOrFail($id);
        }*/
        
        $user = $request->user();
        
        if($user->team_id>0 && $user->game_id!=$input['game_id'])
        {
            return response()->json([
                'game_id' => ['Game couldnt\'t be changed, you are connected to the team.']
            ], 422);
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
        //$user = JWTAuth::parseToken()->authenticate();
        $user = $request->user();
        
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
        //$user = JWTAuth::parseToken()->authenticate();
        $user = $request->user();
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
	 * Get teams of user.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
	public function teams($id, Request $request)
	{
        $team_ids = User::find($id)->teams()
            ->where("team_user.status", 1)
            ->select(['team_id'])
            ->pluck('team_id')
            ->all();
            
        $teams = Team::whereIn("id", $team_ids);
              
        return ApiHelper::parseMultiple($teams, ['title'], $request->all());
	}
    
    /**
	 * Get user's team.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
    public function team($id)
    {
        $team = User::find($id)->team()->get();
        return response()->json($team, 200);
    }
    
    /**
     * Get all figths of all user's teams
     */
    public function fights($id, Request $request)
    {
        $team_ids = User::find($id)->teams()
            ->where("team_user.status", 1)
            ->select(['team_id'])
            ->pluck('team_id')
            ->all();
            
        $teams = Team::whereIn("id", $team_ids)->with(['fights']);
        
        $fight_ids = [];
        $fights = [];
        foreach($teams->get() as $team)
        {
            foreach($team->fights as $fight)
            {
                $fight_ids[] = $fight->id;
            }
        }
        
        if(count($fight_ids)>0)
        {
            $fight_ids = array_unique($fight_ids);
            $fights = Fight::whereIn("id", $fight_ids);
            
            return ApiHelper::parseMultiple($fights, ['title'], $request->all());
        }else{
            return response()->json([], 200);
        }
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