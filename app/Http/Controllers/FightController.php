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
        /*$id = Str::slug(implode(Request::all()));
        $figths = Cache::remember('figths' . $id, 60, function(){
            return Fight::published()->orderBy('id', 'asc')->paginate(12);
        });*/       
        
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
        $request['start_at'] = substr($request->get('start_at_date'), 0, 10)." ".$request->get('start_at_time');
        $rules = [
            'title' => 'required|max:255',
            'game_id'  => 'required|regex:#^[0-9]+$#',
            'type' => 'required',
            'start_at' => 'required|date_format:Y-m-d H:i:s',//|after:now',
        ];
        $input = $request->all();
        if($input['type']=='team')
        {
            $rules['team_id'] = 'required|regex:#^[0-9]+$#';
        }
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) 
        {
            return response()->json($validator->messages(), 422);
        }else{
            
            unset($input["start_at_date"]);
            unset($input["start_at_time"]);
            $input['created_id'] = $request->user()->id;
            
            $input['active'] = 0;
            
            $arGame = Game::findOrFail($input["game_id"]);
            $input['count_team_users'] = intval($arGame["min_players"]);
            
            $result = Fight::create($input);
            if($input['type']=='team')
            {
                FightTeam::create([
                    'team_id' => $input["team_id"],
                    'fight_id' => $result->id
                ]);
            }else{
                FightUser::create([
                    'user_id' => $request->user()->id,
                    'fight_id' => $result->id
                ]);
            }
            
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
    
    public function teams($id, Request $request)
    {
        $team_ids = Fight::find($id)->teams()
            ->select(['team_id'])
            ->pluck('team_id')
            ->all();
            
        $teams = Team::whereIn("id", $team_ids);
              
        return ApiHelper::parseMultiple($teams, ['title'], $request->all());
    }
    
    public function users($id, Request $request)
    {
        $user_ids = Fight::find($id)->users()
            ->select(['user_id'])
            ->pluck('user_id')
            ->all();
            
        $users = User::whereIn("id", $user_ids);
              
        return ApiHelper::parseMultiple($users, [], $request->all());
    }
}
