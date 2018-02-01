<?php

//Olli Kärki

class User extends BaseModel{
    
    public $id, $name, $description, $addministrator, $password;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    
}

