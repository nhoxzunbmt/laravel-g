<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Fight;
use App\Models\Team;
use App\Models\FightTeam;
use App\Models\FightUser;
use App\Models\Game;
use App\User;
use Illuminate\Http\Request;
use Cache;
use Validator;
use App\Acme\Helpers\ApiHelper;

class FightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $figths = new Fight();      
        return ApiHelper::parseMultiple($figths, ['title', 'game_id'], $request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $rules = [
            'title' => 'required|max:255',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'bet'   => 'required|integer'
        ];
        $input = $request->all();  
        $start = $input["start_at"];
        $team_against_id = $input['team_id'];
        $input['title'] = $request['title'] = "Team #".$request->user()->team_id." vs Team #".$team_against_id;
              
        $count = FightTeam::where('team_id', '=', $request->user()->team_id)->
            whereHas('fight', function($fightQuery) use ($start){
                $fightQuery->where('start_at', $start);
                $fightQuery->where('status', '<>', 2);
            })->count();
                  
        if($count>0)
        {
            return response()->json([
                'error' => 'Team has fight on this time!'
            ], 422);
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) 
        {
            return response()->json($validator->messages(), 422);
        }else{

            unset($input["team_id"]);
            $userTeam = $request->user()->team()->first();
            $arGame = $request->user()->game()->first();
            
            if($userTeam->capt_id!=$request->user()->id)
            {
                return response()->json([
                    'created_id' => ['Only captain can create the battle.']
                ], 422);
            }
            
            $input['created_id'] = $request->user()->id;
            $input['created_team_id'] = $request->user()->team_id;
            $input['game_id'] = $arGame->id;
            $input['count_parts'] = 2;  //In future we can change it!
            
            $result = Fight::create($input);
            
            FightTeam::create([
                'team_id' => $request->user()->team_id,
                'fight_id' => $result->id,
                'status' => 1
            ]);
            
            FightTeam::create([
                'team_id' => $team_against_id,
                'fight_id' => $result->id,
                'status' => 0
            ]);
            
            //TODO: Add email to captain of team against
            
            return response()->json($result, 200); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request  $request)
    {
        $fight = new Fight();
        return ApiHelper::parseSingle($fight, $id, $request->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
     * Get all teams of fight
     */
    public function teams($id, Request $request)
    {
        $team_ids = Fight::find($id)->teams()
            ->select(['team_id'])
            ->pluck('team_id')
            ->all();
            
        $teams = Team::whereIn("id", $team_ids);
              
        return ApiHelper::parseMultiple($teams, ['title'], $request->all());
    }
    
    
    /**
     * Get users of fight through the teams
     */
    public function users($id, Request $request)
    {
        $team_ids = Fight::find($id)->teams()
            ->select(['team_id'])
            ->pluck('team_id')
            ->all();
            
        $users = User::whereIn("team_id", $team_ids);
              
        return ApiHelper::parseMultiple($users, [], $request->all());
    }
}
