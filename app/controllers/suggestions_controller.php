<?php

// Olli Kärki

class SuggestionController extends BaseController {

    public static function suggestions() {
        $suggestions = GameSuggestion::all();
        View::make('suggestions.html', array('suggestions' => $suggestions));
    }

    public static function remove() {
        
        $logged = BaseController::get_user_logged_in();
        
        if(!$logged){
            Redirect::to('/login');
        }

        $params = $_POST;

        $suggestionid = $params['suggestionid'];
        $accountid = $params['accountid'];

        GameSuggestion::remove($accountid, $suggestionid);
        
        $account = User::find($_SESSION['username'], $_SESSION['password']);
        $suggestions = GameSuggestion::findByUser($accountid);
        View::make('account.html', array('account' => $account, 'suggestions' => $suggestions));
    }

    public static function suggestgame() {
        
        $logged = BaseController::get_user_logged_in();
        
        if(!$logged){
            Redirect::to('/login');
        }
        
        View::make('game_suggest.html');
    }
    
    public static function suggest(){
        
        $logged = BaseController::get_user_logged_in();
        
        if(!$logged){
            Redirect::to('/login');
        }
        
        $params = $_POST;
        
        $title = $params['title'];
        $publisher = $params['publisher'];
        $account_id = User::find($_SESSION['username'], $_SESSION['password'])->id;
        
        GameSuggestion::suggest($title, $publisher, $account_id);
        
        Redirect::to('/suggestions');
        
    }

    public static function addgame() {
        
        $admin = BaseController::get_admin_logged_in();
        
        if(!$admin){
            Redirect::to('/');
        }
        
        View::make('game_add.html', array('suggestion' => null));
    }
    
    public static function moveToGame($id){
        
        $admin = BaseController::get_admin_logged_in();
        
        if(!$admin){
            Redirect::to('/');
        }
        
        $suggestion = GameSuggestion::find($id);
        
        if($suggestion == null){
            Redirect::to('/addgame');
        }
        
        View::make('game_add.html', array('suggestion' => $suggestion));
    }
    
    public static function listGame(){
        
        $admin = BaseController::get_admin_logged_in();
        
        if(!$admin){
            Redirect::to('/');
        }
        
        $params = $_POST;
        
        $name = $params['title'];
        $description = $params['summary'];
        $date = $params['date'];
        
        if($date == null){
            $date = "1337-01-11";
        }
        
        $publisher = $params['publisher'];
        
        Game::save($name, $description, $publisher, $date);
        
        Redirect::to('/games');
    }

}
