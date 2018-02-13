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
            Redirect::to('/login', array('message' => 'The username or password was invalid!'));
        }
    }

    public static function logout() {

        session_unset();

        Redirect::to('/');
    }

    public static function getAccount() {

        self::check_logged_in();

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
            View::make('login.html');
        }
    }

    public static function login() {
        View::make('login.html');
    }

    public static function register() {
        
        $params = $_POST;

        $username = $params['username'];
        $password1 = $params['password1'];
        $password2 = $params['password2'];
        
        $error = User::validate($username, $password1, $password2);
        
        if(count($error) == 0){

            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password1;
            
            User::createUser($username, $password1);
            
            Redirect::to('/profile' );
            
        } else {
            
            Redirect::to('/register', array('error' => $error, 'username' => $username, 'p1' => $password1));
            
        }
        
    }
    
    public static function getregister(){
        View::make('register.html');
    }
    
}
