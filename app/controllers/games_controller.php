<?php

// Olli Kärki

class GameController extends BaseController {

    public static function gamelist($page, $rule) {

        if(!ctype_digit(strval($page))){
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }
        
        $games = Game::all($rule, $page);
        $pages = Game::pages();
        $showpage = true;
        if($pages == 1){
            $showpage = false;
        }
        View::make('game_list.html', array('games' => $games, 'rule' => $rule, 'pages' => $pages, 'showpage' => $showpage));
    }

    public static function search(){
        
        $params = $_POST;
        $search = $params['search'];
        if($search == null){
            Redirect::to('/games/1/name');
        }
        $games = Game::search($search);
        View::make('game_list.html', array('games' => $games, 'search' => $search, 'showpage' => false));
        
        
    }
    
    public static function showgame($id) {

        if(!ctype_digit(strval($id))){
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }
        
        $game = Game::find($id);
        
        if($game == null){
            Redirect::to('/games/1/name', array('error' => "The game you were looking for could't be found!"));
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
            Redirect::to('/games/1/name', array('error' => "The game you were looking for could't be found!"));
        }
        
        $categories = Category::all();
        
        View::make('game_edit.html', array('game' => $game, 'categories' => $categories));
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
        $category = Category::getId($params['category'])->id;
        
        $error = Game::validate($date, $summary, $name, $publisher);
        if (count($error) == 0) {
            Game::update($name, $date, $publisher, $summary, $id, $category);
            Redirect::to('/game/' . $id);
        } else {
            $game = array('name' => $name, 'publishingdate' => $date, 'publisher' => $publisher, 'category' => $params['category'], 'id' => $id, 'description' => $summary);
            Redirect::to('/editgame/' . $id, array('error' => $error, 'game' => $game));
        }
    }

    public static function deletegame($id) {

        if(!ctype_digit(strval($id))){
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/', array('error' => 'You are not an admin!'));
        }

        $game = Game::delete($id);

        Redirect::to('/games/1/name');
    }

}
