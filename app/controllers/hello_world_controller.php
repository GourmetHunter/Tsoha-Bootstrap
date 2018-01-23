<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      View::make('home.html');
    }

    public static function account(){
      // Testaa koodiasi täällä
      View::make('account.html');
    }
    
    public static function gamelist(){
        View::make('game_list.html');
    }
    
    public static function login(){
        View::make('login.html');
    }
    
    public static function showgame(){
        View::make('game_show.html');
    }
    
    public static function editgame(){
        View::make('game_edit.html');
    }
    
    public static function addgame(){
        View::make('game_add.html');
    }
    
    public static function suggestgame(){
        View::make('game_suggest.html');
    }
    
    public static function suggestions(){
        View::make('suggestions.html');
    }
    
  }
