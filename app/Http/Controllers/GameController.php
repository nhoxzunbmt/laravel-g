<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GiantBomb\Client\GiantBombClient;
use App\Acme\Helpers\TwitchHelper;
use Illuminate\Support\Str;
use App\Models\Genre;
use App\Models\Game;
use Storage;
use Image;
use File;
use Cache;
use ApiHandler;
use App\Acme\Helpers\ApiHelper;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$id = Str::slug(implode($request->all()));
        //$items = Cache::remember('games'.$id, 60, function() use ($request){
        //    return Game::search($request->all())->active()->orderBy('id', 'asc')->paginate(6);
        //});

        $items = Game::search($request->all())->active();

        if($request->has('show_all')) 
        {
            $items = $items->select(['id', 'title'])->orderBy('title', 'asc')->get();
        }else{
            $items = $items->orderBy('id', 'asc')->paginate(12)->appends('page');
        }
            
        return response()->json($items);
        */
        $games = Game::active();      
        return ApiHelper::parseMultiple($games, ['title', 'genre_id'], $request->all());
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        /*try
        {
            $game = Cache::remember('game' . $id, 60, function() use ($id){
                $game = Game::with(['genre' => function($query){
                    $query->select('id', 'title');
                }])->findOrFail($id);
                $game->images = json_decode($game->images, true);
                return $game;
            });
        }
        catch(ModelNotFoundException $e)
        {
            abort(404);
        }
        
        return response()->json($game);*/
        $game = new Game();
        return ApiHelper::parseSingle($game, $id, $request->all());
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
     * Import games from Giantbomb.com and Twitch by API.
     */
    public function importByTwitchGiantbomb()
    {
        $arGenres = Genre::all()->pluck('id', 'giantbomb_id');
        
        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => env('TWITCH_API_CLIENT_ID'),
        ]);
        
        $giantbombClient = GiantBombClient::factory([
            'apiKey' => env('GIANTBOMB_API_CLIENT'),
        	'cache'  => null
        ]);
        
        $count = 0;
        $limit = 50;
        $offset = 0;
        do{
            $responseTwitch = $twitchClient->getTopGames((int)$limit, (int)$offset);
            $total = intval($responseTwitch['_total']);
            $games = $responseTwitch["top"];
            
            foreach($games as $arGame)
            {
                $giantbomb_id = $arGame["game"]["giantbomb_id"];
                $twitch_id = $arGame["game"]["_id"];
                
                //Get Game by Giant id
                $response = $giantbombClient->getGame(['id' => $giantbomb_id]);
                if( $response->getStatusCode() === 1 ) 
                {        
                    $game = $response->getResults();
                    
                    try{
                        if($game->hasGenres())
                        {
                            $genres = $game->getGenres();
                            $genre_id = $genres[0]['id'];
                        }else{
                            $genre_id = 1;
                        }
                    }
                    catch(Exception $e)
                    {
                        $genre_id = 1;
                    }
                                        
                    $logo = null;
                    if(!empty($arGame['game']['box']['large']))
                    {
                        $path = str_replace("https", "http", $arGame['game']['box']['large']);
                        $extension = strtolower(File::extension(basename($path)));
                        
                        if(in_array($extension, ["jpg", "jpeg", "png"]) && !empty($path))
                        {
                            $logo = 'games/'.basename($path);
                            $logo = str_replace(array("%", "+", ":"), "", $logo);
                            Image::make($path)->save(public_path("storage/".$logo));
                        }
                    }
                    
                    $arImages = [];
                    $images = $game->getImages();                
                    foreach($images as $arImage)
                    {
                        $path = $arImage['medium_url'];
                        $extension = strtolower(File::extension(basename($path)));
                        if(in_array($extension, ["jpg", "jpeg", "png"]) && !empty($path) && @getimagesize($path))
                        {
                            $image = 'games/'.basename($path);
                            $image = str_replace(array("%", "+", ":"), "", $image);
                            Image::make($path)->save(public_path("storage/".$image));
                            $arImages[] = $image;
                            
                            if(count($arImages)==6) break;
                        }
                    }
                    
                    $overlay = null;
                    foreach($images as $arImage)
                    {
                        $path = $arImage['screen_url'];
                        $extension = strtolower(File::extension(basename($path)));
                        if(in_array($extension, ["jpg", "jpeg", "png"]) && !empty($path) && @getimagesize($path))
                        {
                            $image = 'games/'.basename($path);
                            $image = str_replace(array("%", "+", ":"), "", $image);
                            Image::make($path)->save(public_path("storage/".$image));
                            $overlay = $image;
                            
                            break;
                        }
                    }
                    

                    if($logo===NULL && count($arImages)>0)
                    {
                        $logo = $arImages[0];
                    }
                    
                    $data = [
                        'active'      => true,
                        'title'       => $game->getName(),
                        'giantbomb_id'=> $giantbomb_id,
                        'twitch_id'   => $twitch_id,
                        'genre_id'    => $arGenres[$genre_id],
                        'images'      => json_encode($arImages),
                        'logo'        => $logo,
                        'overlay'     => $overlay,  
                        'body'        => $game->getDeck(),
                        'online'      => true,
                        'alias'       => $game->getAliases()
        	        ];
    
        	        Game::create($data);
                }
            }
            
            $count+= count($games);
            $offset+= $limit;
            
            break;
            
        } while ($count<$total);
    }
}