<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use App\Models\Game;
use JWTAuth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        
        return response()->json($result);        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $team = Team::with(['users' => function($query){
                $query->select('id', 'name', 'email');
            }])->findOrFail($id);
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
                $query->select('name', 'email');
            }])->findOrFail($id);
            
            
            /*if($team->capt_id!=$user->id)
            {
                return response()->json(["error" => "Only captain can edit data."], 404);
            }*/
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
}
