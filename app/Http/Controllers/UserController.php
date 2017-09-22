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
use Hootlex\Friendships\Models\Friendship;
use Hootlex\Friendships\Status;
use App\Acme\Helpers\ScheduleHelper;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */     
    public function index(Request $request)
    {
        $users = new User();
        return ApiHelper::parseMultiple($users, ['name', 'last_name', 'email'], $request->all());
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
        $user = $request->user();
        
        //Game needs for player
        if(($input['type']=='player' || $user->type=='player') && empty($input['game_id']))
        {
            return response()->json([
                'game_id' => ['Game needs for creation the team and fights.']
            ], 422);
        }
        
        if($user->team_id>0 && $user->game_id!=$input['game_id'])
        {
            return response()->json([
                'game_id' => ['Game couldn\'t be changed, you are connected to the team.']
            ], 422);
        }
        
        if ($request->has('password')) 
            $user->password = bcrypt($request->password);
        
        //If confirmed -> active 
        if($user->confirmed)
        {
            $user->active = 1;
        }
        
        //If team exist & active Player cannot change streams
        if($user->team_id>0 && $request->user()->team()->first()->status==Team::ACCEPTED)
        {
            unset($input['streams']);
        }
        elseif($input['streams'])
        {
            $streams = [];
            foreach($input['streams'] as $arStream)
            {
                if(!empty($arStream["value"]))
                {
                    $streams[] = ['value' => trim($arStream["value"])];
                }
            }
            $input['streams'] = $streams;
        }
        
        if(!empty($input['schedule']))
        {
            $input['schedule'] = ScheduleHelper::modifyForTwoWeeks($input['schedule']);
            //usort($input['schedule'], 'sortSchedule');
        }
        
        if($result = $user->update($input))
        {
            return response()->json($user);
        }
        
        return response()->json([
            'update' => 'Something wrong'
        ], 422);
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
        
        return response()->json($user, 200);
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
        
        return response()->json($user, 200);
	}
    
    
    
    /*
    public static function invitations($id, Request  $request)
    {
        $team_users = TeamUser::where("user_id", $id);      
        return ApiHelper::parseMultiple($team_users, [''], $request->all());
    }
    
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

    public function invitesToTeam($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        $items = $user->teams()->with(['game'])
            ->where("team_user.status", 0)
            ->where("team_user.sender_id", "<>", $user->id)
            ->get();
        
        return response()->json($items, 200);
    }*/
    
        
    /**
	 * Get user's team.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
    public function team($id)
    {
        $team = User::findOrFail($id)->team()->first();
        return response()->json($team, 200);
    }
    
    /**
	 * Get user's friends.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
    public function friends($id, Request $request)
    {
        $user = User::findOrFail($id); //$request->user();
                
        $friendships = $user->getAcceptedFriendships();
        $recipients  = $friendships->pluck('recipient_id')->all();
        $senders     = $friendships->pluck('sender_id')->all();

        $users = $user->where('id', '!=', $user->getKey())
            ->whereIn('id', array_merge($recipients, $senders))
            ->where('type', $user->type)->active();

        return ApiHelper::parseMultiple($users, ['name', 'last_name', 'email'], $request->all());
        //return response()->json($result, 200);
    }
}