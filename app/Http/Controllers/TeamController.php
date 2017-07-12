<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\Game;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Image;

class TeamController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $items = Team::search($request->all())->with(['users', 'game'])->orderBy('id', 'asc')->paginate(12)->appends('page');                    
        return response()->json($items);
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
        $arGame = Game::findOrFail($input["game_id"]);
        if(intval($input["quantity"]) < intval($arGame["min_players"]))
        {
            return response()->json([
                'quantity' => ['The quantity must be at least '.$arGame["min_players"]]
            ], 422);
        }
        
        $input['capt_id'] = $request->user()->id;
        $result = Team::create($input);
        
        /**
         * Add player to team
         */
        TeamUser::create([
            "user_id" => $request->user()->id,
            "team_id" => $result->id,
            "sender_id" => $request->user()->id,
            "status" => TeamUser::ACCEPTED
        ]);
        
        return response()->json($result);        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        try
        {
            $team = Team::with(['game', 'users' => function($query){
                $query->select('name', 'email', 'avatar', 'status');
            }])->where('id', $param)
                ->orWhere('slug', $param)
                ->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([], 404);
        }
        
        return response()->json($team);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        try
        {
            $team = Team::with(['users' => function($query){
                $query->select('name', 'email', 'avatar', 'status', 'sender_id');
            }])->findOrFail($id);
            
            if($team->capt_id!=$user->id)
            {
                return response()->json(["error" => "Only captain can edit data."], 404);
            }
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([], 404);
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
     * Get users of the team
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function users($id)
    {
        try
        {
            $users = Team::findOrFail($id)->users()->select('name', 'email', 'avatar', 'status');
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([], 404);
        }
        
        return response()->json($users);
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
        $user = $request->user();
    
        $TeamUser = TeamUser::where("user_id", $userId)->where("team_id", $teamId)->first();
    
        if($TeamUser)
        {   
            $input = $request->all();
            $TeamUser->update($input);
            
            return response()->json($TeamUser);
        }else{
    
            $team = Team::findOrFail($teamId);
            
            if($team->capt_id!=$user->id)
            {
                //check user add himself to the team
                if($userId!=$user->id)
                {
                    return response()->json(["error" => "Only captain can add new user to team."], 404);
                }
            }
            
            $result = TeamUser::create([
                "user_id" => $userId,
                "team_id" => $team->id,
                "sender_id" => $request->user()->id,
                "status" => TeamUser::PENDING
            ]);
        }
        
        return response()->json([
            'success' => true,
            '_id'=> $result->id
        ], 200);
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
            ], 200);
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
}
