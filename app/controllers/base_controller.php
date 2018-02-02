<?php

class BasicController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function account() {

        $params = $_POST;

        $username = $params['username'];
        $password = $params['password'];

        $account = null;

        if ($username != null && $password != null) {
            $account = User::find($username, $password);
        }

        if ($account != null) {

            $_SESSION['username'] = $account->name;
            $_SESSION['password'] = $account->password;

            $id = $account->id;
            $suggestions = GameSuggestion::findByUser($id);
            Redirect::to('/profile', array('account' => $account, 'suggestions' => $suggestions));
        } else {
            Redirect::to('/login', array('failure' => true));
        }
    }

    public static function logout() {

        session_unset();

        Redirect::to('/');
    }

    public static function getAccount() {

        $user = BaseController::get_user_logged_in();

        if (!$user) {
            Redirect::to('/');
        }

        $username = $_SESSION['username'];
        $password = $_SESSION['password'];

        $account = null;

        if ($username != null && $password != null) {
            $account = User::find($username, $password);
        }

        if ($account != null) {

            $_SESSION['username'] = $account->name;
            $_SESSION['password'] = $account->password;

            $id = $account->id;
            $suggestions = GameSuggestion::findByUser($id);
            View::make('account.html', array('account' => $account, 'suggestions' => $suggestions));
        } else {
            View::make('login.html', array('failure' => true));
        }
    }

    public static function login() {
        View::make('login.html', array('failure' => false));
    }

}
