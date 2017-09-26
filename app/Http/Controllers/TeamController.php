<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\Game;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Image;
use Mail;
use ApiHandler;
use App\Acme\Helpers\ApiHelper;
use App\Mail\EmailVerify;
use App\Mail\InvitationToTeam;
use App\Http\Requests\TeamStoreRequest;
use App\Acme\Helpers\ScheduleHelper;

class TeamController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $teams = new Team();      
        return ApiHelper::parseMultiple($teams, ['title'], $request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamStoreRequest $request)
    {
        $input = $request->all();
        
        //Validation min game's players count
        if(!$request->user()->game_id)
        {
            return response()->json([
                'title' => ['Before create a team You must set the in profile']
            ], 422);
        }
        $arGame = Game::findOrFail($request->user()->game_id);
        $input['game_id'] = $arGame["id"];
        $input["quantity"] = $arGame["min_players"];
        $input["created_at"] = \Carbon\Carbon::now()->timestamp;

        //Check if exist team
        if($request->user()->team_id>0/* && $request->user()->team()->first()->status==Team::ACCEPTED*/)
        {
            return response()->json([
                'title' => ['You are already have the team! If you want to create new team delete all users from your current team and then delete the team!']
            ], 422);
        }
        
        $input['capt_id'] = $request->user()->id;
        $result = Team::create($input);
        
        //Check schedule
        $arSchedules = $request->user()->schedule();
        usort($arSchedules, 'sortSchedule');
        $blockSchedules = ScheduleHelper::getCrossingBlocks($arSchedules);
        $blockSchedules = ScheduleHelper::modifyForTwoWeeks($blockSchedules);
        if(count($blockSchedules)==0)
        {
            return response()->json([
                'schedule' => ['Your schedule has to consist of mininmum 1 three hours block!']
            ], 422);
        }        
        
        $input['schedule'] = $blockSchedules;
        
        /**
         * Add player to team
         */
        TeamUser::create([
            "user_id" => $request->user()->id,
            "team_id" => $result->id,
            "sender_id" => $request->user()->id,
            "status" => TeamUser::ACCEPTED,
            "start_at" => \Carbon\Carbon::now()->timestamp
        ]);
        
        $user = $request->user();
        $user->update(['team_id' => $result->id]);
        
        return response()->json($result);        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($param, Request  $request)
    {      
        if ( is_numeric($param) ) 
        {
            $filterBy = ['id' => $param];
        }else{
            $filterBy = ['slug' => $param];
        }
        
        $team = new Team();
        return ApiHelper::parseSingle($team, $filterBy, $request->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request  $request)
    {
        $user = $request->user();
    
        $team = new Team();
        $response = ApiHelper::parseSingle($team, ['id' => $id], $request->all());
        $team = json_decode($response->content(), true);
        
        if($team['capt_id']!=$user->id)
        {
            return response()->json(["error" => "Only captain can edit data."], 404);
        }
            
        return response()->json($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, TeamUpdateRequest $request)
    {
        $input = $request->all();
        $team = Team::findOrFail($id);
        unset($input['users']);
        $team->update($input);
        
        return response()->json($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Get invitations to team
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function invitations($id, Request  $request)
    {
        $team_users = TeamUser::where("team_id", $id);      
        return ApiHelper::parseMultiple($team_users, [''], $request->all());
    }
    
    /**
	 * Update avatar.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return Response
	 */
	public function logo(Request $request)
	{
        $params = $request->all();
        $user = JWTAuth::parseToken()->authenticate();
        
        $team = Team::findOrFail($params["id"]);
        
        if($team->image)
        {
            $path = public_path() . '/storage/' . $team->image;
            if(file_exists($path)) 
            {
                unlink($path);
            }
        }
            
        $path = Storage::disk('public')->putFile(
            'teams', $request->file('files')
        );
        
        /**
         * Crop & resize using client crop data
         */
        $img = Image::make('storage/'.$path);
        $img->crop((int)$params["toCropImgH"], (int)$params["toCropImgW"], (int)$params["toCropImgX"], (int)$params["toCropImgY"]);
        $img->resize(150, 150);
        $img->save('storage/'.$path);
        $img->destroy();
        
        $team->image = $path;
        $team->update();
        
        return response()->json($team);
	}
    
    /**
	 * Update overlay.
     * 
	 * @param  \Illuminate\Http\Request  $request
	 * @return Response
	 */
	public function overlay(Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();
        $params = $request->all();
        $team = Team::findOrFail($params["id"]);
        
        if($team->overlay)
        {
            $path = public_path() . '/storage/' . $team->overlay;
            if(file_exists($path)) 
            {
                unlink($path);
            }
        }
            
        $path = Storage::disk('public')->putFile(
            'teams', $request->file('files')
        );
        $team->overlay = $path;
        $team->update();
        
        return response()->json($team);
	}
    
    /**
     * Send invite user to the team
     *
     * @param  int  $teamId
     * @param  int  $userId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inviteUser($teamId, $userId, Request $request)
    {
        $user = User::findOrFail($userId);
        $userTeam = $user->team()->first();
        if($userTeam && $userTeam->id>0 && $user->free_player)
        {
            return response()->json([
                "error" => "The user is already in another team"
            ], 422);
        }
        
        //Check user in team
        $TeamUser = TeamUser::where("user_id", $userId)->where("team_id", $teamId)->first();
    
        if($TeamUser)
        {   
            if(isset($input['status']) && $input['status']==1)
            {
                $blockSchedules = self::checkCrossingScheduleTeamUser($teamId, $userId);
                if(count($blockSchedules)==0)
                {
                    return response()->json([
                        "error" => "User don't have 3-hours blocks crossing with team schedule. Edit schedule before connect to the team."
                    ], 422);
                }
            }
            
            //update TeamUser
            $input = $request->all();
            $TeamUser->update($input);
            
            //update User team_id
            if(isset($input['status']) && $input['status']==1)
            {                
                //Change team & and delete
                if($user->team_id>0 && $user->team_id!=$teamId)
                {
                    if($userTeam->capt_id==$user->id)
                    {
                        TeamUser::where("team_id", $user->team_id)->delete();
                        Team::find($user->team_id)->delete();
                    }else{
                        TeamUser::where("user_id", $user->id)->where("team_id", $user->team_id)->delete();
                    }
                }
                
                $user->update([
                    'team_id' => $teamId
                ]);
            }
            
            //If count users in team more 1 update Team status
            $team = Team::findOrFail($teamId);
            $countUsers = $team->users()->count();
            if($countUsers>1)
            {
                $team->update([
                    'status' => Team::ACCEPTED
                ]);
                
                //update User free_player
                foreach($team->users()->get() as $player)
                {
                    $player->update([
                        'free_player' => false
                    ]);
                    
                    $TeamUser = TeamUser::where("user_id", $player->id)->where("team_id", $teamId)->first();
                    $TeamUser->update([
                        'start_at' => \Carbon\Carbon::now()
                    ]);
                }
            }
            
            self::updateSchedule($teamId);
            
            return response()->json($TeamUser);
        }else{
    
            $team = Team::findOrFail($teamId);
            
            //Check if current user is captain
            if($team->capt_id!=$request->user()->id)
            {
                //check user add himself to the team. User sends request to the captain of the team.
                if($userId!=$request->user()->id)
                {
                    return response()->json([
                        "error" => "Only captain can send invitation to user to team."
                    ], 422);
                }
                
                $blockSchedules = self::checkCrossingScheduleTeamUser($teamId, $userId);
                if(count($blockSchedules)==0)
                {
                    return response()->json([
                        "error" => "You don't have 3-hours blocks crossing with team schedule. Edit you schedule before connect to the team."
                    ], 422);
                }
                
                if(!$user->game_id)
                {
                    $user->update([
                        'game_id' => $team->game_id
                    ]);
                }
            }
                      
            $result = TeamUser::create([
                "user_id" => $userId,
                "team_id" => $team->id,
                "sender_id" => $request->user()->id,
                "status" => TeamUser::PENDING
            ]);
            
            if($request->user()->id==$team->capt_id)
            {
                $content = [
            		'url' => url(config('app.url')."/teams/".$team->slug),
                    'title' => $request->user()->name.' invites you to the team '.$team->title,
            		'button' => 'Click Here'
          		];
        
            	Mail::to($user->email)->send(new InvitationToTeam($content));
            }else{
                
                $captain = $team->captain()->get();
                
                $content = [
            		'url' => url(config('app.url')."/players/".$user->id),
                    'title' => $user->name.' wants to be a part of team '.$team->title,
            		'button' => 'Watch the player'
          		];
        
            	Mail::to($captain->email)->send(new InvitationToTeam($content));
            }
        }
        
        return response()->json([
            'success' => true,
            '_id'=> $result->id
        ], 200);
    }
    
    /**
     * Get users of the team
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function users($id)
    {
        $users = Team::findOrFail($id)->users();
        return ApiHelper::parseMultiple($users, ['name', 'last_name'], $request->all());
    }
    
    public function getRequestsOut($id, Request $request)
    {
        $user = $request->user();
        
        $query = TeamUser::where("sender_id", $user->id)
            ->where("team_id", $id);
        
        if($request->has('status'))
        {
            $query = $query->where("status", (int)$request->get('status'));
        }
        
        $result = $query->get();
            
        if($result)
        {
            return response()->json([
                'success' => true
            ], 200);
        }else{
            return response()->json([
                'error' => 'Error of system. Try to make it later'
            ], 422);
        }
    }    
    
    /**
     * Get a list of potential team players
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchPotentialUsers($id, Request $request)
    {
        $user = $request->user(); 
        $error = ['error' => 'No results found, please try with different keywords.'];
        $teamUsers = TeamUser::where('team_id', $id)->select(['user_id'])->get();

        if($request->has('q'))
        {
            $items = User::search($request->get('q'))
                ->whereNotIn('id', $teamUsers)
                ->where('type', $user->type)->active()
                ->select(['id', 'name', 'last_name', 'avatar', 'nickname', 'type'])->paginate(12);
            
            if($items->count()==0)
                return response()->json($error);

            return response()->json($items);
        }
        
        return response()->json($error);
    }
    
    public function linkToInvestor($id, Request $request)
    {
        $user = $request->user();
        $team = Team::findOrFail($id);
        
        $content = [
    		'url' => url(config('app.url')."/teams/".$team->slug),
            'title' => '<a href="'.url(config('app.url')."/players/".$user->id).'">'.$user->name.'</a> sended you link to the team you can invest. Look at this!',
    		'button' => 'Click Here'
  		];

    	Mail::to($request->get('email'))->send(new TeamLinkToInvestor($content));
        
        return response()->json(null, 200);
    }
    
    
    /**
     * Check crossing schedule of team and new user
     * @param  int  $teamId
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public static function checkCrossingScheduleTeamUser($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);
        $users = $team->users();
        $player = User::findOrFail($userId)->get();
        
        $arSchedules = ScheduleHelper::getCrossingSchedule([$users->get(), $player]);
        $blockSchedules = ScheduleHelper::getCrossingBlocks($arSchedules);
        $blockSchedules = ScheduleHelper::modifyForTwoWeeks($blockSchedules);
        
        return $blockSchedules;
    }
    
    public static function updateSchedule($id)
    {
        $team = Team::findOrFail($id);
        $users = $team->users();

        $arSchedules = ScheduleHelper::getCrossingSchedule($users->get());
        $blockSchedules = ScheduleHelper::getCrossingBlocks($arSchedules);
        $blockSchedules = ScheduleHelper::modifyForTwoWeeks($blockSchedules);
        
        //send notify captain none crossing schedule
        if(count($blockSchedules)==0)
        {
            
        }
        
        $team->update([
            'schedule' => $blockSchedules
        ]);
    }
}