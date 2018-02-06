<?php

// Olli Kärki

class GameController extends BaseController {

    public static function gamelist() {

        $games = Game::all();
        View::make('game_list.html', array('games' => $games));
    }

    public static function showgame($id) {

        if(!ctype_digit(strval($id))){
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }
        
        $game = Game::find($id);
        
        if($game == null){
            Redirect::to('/games', array('error' => "The game you were looking for could't be found!"));
        }
        
        $reviews = Review::findByGame($id);
        $score = Review::score($id);

        View::make('game_show.html', array('game' => $game, 'reviews' => $reviews, 'score' => $score));
    }

    public static function editgame($id) {

        if(!ctype_digit(strval($id))){
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/', array('error' => "You are not an administrator!"));
        }

        $game = Game::find($id);
        
        if($game == null){
            Redirect::to('/games', array('error' => "The game you were looking for could't be found!"));
        }
        
        View::make('game_edit.html', array('game' => $game));
    }

    public static function saveedit() {

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/');
        }

        $params = $_POST;

        $name = $params['name'];
        $date = $params['date'];
        $publisher = $params['publisher'];
        $summary = $params['summary'];
        $id = $params['id'];

        $error = Game::validate($date, $summary, $name, $publisher);
        if (count($error) == 0) {
            Game::update($name, $date, $publisher, $summary, $id);
            Redirect::to('/game/' . $id);
        } else {
            Redirect::to('/editgame/' . $id, array('error' => $error));
        }
    }

    public static function deletegame($id) {

        if(!ctype_digit(strval($id))){
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/');
        }

        $game = Game::delete($id);

        Redirect::to('/games');
    }

}
