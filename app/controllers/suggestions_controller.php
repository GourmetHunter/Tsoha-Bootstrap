<?php

// Olli Kärki

class SuggestionController extends BaseController {

    public static function suggestions() {
        $suggestions = GameSuggestion::all();
        View::make('suggestions.html', array('suggestions' => $suggestions));
    }

    public static function remove() {

        $logged = BaseController::get_user_logged_in();

        if (!$logged) {
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

        if (!$logged) {
            Redirect::to('/login');
        }

        View::make('game_suggest.html');
    }

    public static function suggest() {

        $logged = BaseController::get_user_logged_in();

        if (!$logged) {
            Redirect::to('/login');
        }

        $params = $_POST;

        $title = $params['title'];
        $publisher = $params['publisher'];
        $account_id = User::find($_SESSION['username'], $_SESSION['password'])->id;

        if (count($error = GameSuggestion::validate($title, $publisher)) == 0) {
            GameSuggestion::suggest($title, $publisher, $account_id);
            Redirect::to('/suggestions');
        } else {
            Redirect::to('/suggest', array('error' => $error));
        }
    }

    public static function addgame() {

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/');
        }

        View::make('game_add.html', array('suggestion' => null));
    }

    public static function moveToGame($id) {

        if (!ctype_digit(strval($id))) {
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/');
        }

        $suggestion = GameSuggestion::find($id);

        if ($suggestion == null) {
            Redirect::to('/addgame');
        }

        View::make('game_add.html', array('suggestion' => $suggestion));
    }

    public static function listGame() {

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/');
        }

        $params = $_POST;

        $name = $params['title'];
        $description = $params['summary'];
        $date = $params['date'];
        $publisher = $params['publisher'];

        $error = Game::validate($date, $description, $name, $publisher);

        if (count($error) == 0) {
            Game::save($name, $description, $publisher, $date);
            Redirect::to('/games');
        } else {
            $suggestion = new GameSuggestion(array(
                'name' => $name,
                'publisher' => $publisher
            ));
            View::make('game_add.html', array('suggestion' => $suggestion, 'previous' => $description, 'error' => $error));
        }
    }

    public static function adminRemoveSuggestion($id) {

        if (!ctype_digit(strval($id))) {
            Redirect::to('/', array('error' => "There was error in the URL! You've been redirected to the frontpage."));
        }

        $admin = BaseController::get_admin_logged_in();

        if (!$admin) {
            Redirect::to('/', array('error' => "You are not an administrator!"));
        }

        $error = GameSuggestion::validateRemove($id);

        if (count($error) == 0) {
            $suggestion = GameSuggestion::find($id);
            GameSuggestion::removeByString($suggestion->name, $suggestion->publisher);
            Redirect::to('/suggestions');
        } else {
            Redirect::to('/suggestions', array('error' => $error));
        }
    }

}
