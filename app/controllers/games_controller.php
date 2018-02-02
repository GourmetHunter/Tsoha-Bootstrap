<?php

// Olli Kärki

class GameController extends BaseController {
    
    public static function gamelist(){
        
        $games = Game::all();
        View::make('game_list.html', array('games' => $games));
        
    }

    public static function showgame($id) {
        
        $game = Game::find($id);
        $reviews = Review::findByGame($id);
        $score = Review::score($id);
        
        View::make('game_show.html', array('game' => $game, 'reviews' => $reviews, 'score' => $score));
        
    }

    public static function editgame($id) {
        
        $admin = BaseController::get_admin_logged_in();
        
        if(!$admin){
            Redirect::to('/');
        }
        
        $game = Game::find($id);
        View::make('game_edit.html', array('game' => $game));
        
    }
    
    public static function saveedit(){
        
        $admin = BaseController::get_admin_logged_in();
        
        if(!$admin){
            Redirect::to('/');
        }
        
        $params = $_POST;
        
        $name = $params['name'];
        $date = $params['date'];
        $publisher = $params['publisher'];
        $summary = $params['summary'];
        $id = $params['id'];
        
        if($date == null){
            $date = "1337-01-11";
        }
        
        Game::update($name, $date, $publisher, $summary, $id);
        
        Redirect::to('/game/' . $id);
        
    }
    
    public static function deletegame($id) {
        
        $admin = BaseController::get_admin_logged_in();
        
        if(!$admin){
            Redirect::to('/');
        }
        
        $game = Game::delete($id);
        
        Redirect::to('/games');
        
    }
    
    
}

