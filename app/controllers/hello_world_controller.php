<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      View::make('home.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      View::make('suggestion.html');
    }
    
    public static function gamelist(){
        View::make('game_list.html');
    }
    
    public static function login(){
        View::make('login.html');
    }
    
  }
