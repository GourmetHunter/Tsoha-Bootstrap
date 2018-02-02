<?php

//Olli Kärki

class User extends BaseModel{
    
    public $id, $name, $administrator, $password;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public static function find($username, $password) {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE Lower(nimi) = Lower(:username) AND password =:password LIMIT 1');

        $query->execute(array('username' => $username, 'password' => $password));
        
        $row = $query->fetch();

        $user = null;
        
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'administrator' => $row['hallinto'],
                'password' => $row['password']
            ));
        }

        return $user;
        
    }
    
}

