<?php

class BaseController {

    public static function get_user_logged_in() {
        
        if(!isset($_SESSION['username'])){
            return null;
        }
        
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        
        $account = User::find($username, $password);

        $r = null;
        
        if($account != null){
            $r = true;
        }

        return $r;
    }

    public static function get_admin_logged_in() {
        
        if(!isset($_SESSION['username'])){
            return null;
        }
        
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        
        $account = User::find($username, $password);

        $r = null;
        
        if($account != null){
            $r = $account->administrator;
        }

        return $r;
        
    }

    public static function check_logged_in() {
        // Toteuta kirjautumisen tarkistus tähän.
        // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
        
        if(!isset($_SESSION['username'])){
            return null;
        }
        
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        
        $account = User::find($username, $password);

        $r = null;
        
        if($account != null){
            $r = true;
        }

        return $r;
    }

}
