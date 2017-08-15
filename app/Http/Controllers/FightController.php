<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Fight;
use App\Models\Game;
//use Request;
use Illuminate\Http\Request;
use Cache;
use Validator;

class FightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        /*$id = Str::slug(implode(Request::all()));
        $figths = Cache::remember('figths' . $id, 60, function(){
            return Fight::published()->orderBy('id', 'asc')->paginate(12);
        });*/       
        
        $figths = Fight::orderBy('start_at', 'desc')->with(['game', 'teams', 'users'])->paginate(12);
        return response()->json($figths);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $games = Game::orderBy('title', 'asc')->pluck('title', 'id');
        return view('fight.create')->with(compact('games'));
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'game_id'  => 'required|regex:#^[0-9]+$#',
            'type' => 'required',
            'start_at' => 'required|date_format:Y-m-d H:i:s',//|after:now',
        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->messages(), 422);
        }else{
            $input = $request->all();
            unset($input["start_at_date"]);
            unset($input["start_at_time"]);
            $input['created_id'] = $request->user()->id;
            
            $arGame = Game::findOrFail($input["game_id"]);
            $input['count_team_users'] = intval($arGame["min_players"]);
            
            $result = Fight::create($input);
            return response()->json($result, 200); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
