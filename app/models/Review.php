<?php

//Olli Kärki

class Review extends BaseModel{
    
    public $user, $game, $score, $content, $date;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    
}

