<?php

//Olli Kärki

class User extends BaseModel{
    
    public $id, $name, $administrator, $password;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    public static function validate($username, $pass1, $pass2){
        
        if(strpos($username, ' ') !== false){
            return 'The username may not contain spaces!';
        }
        
        if(strlen($username) < 4){
            return 'The username is too short! Use atleast 4 characters!';
        }
        
        if(strlen($pass1) < 5){
            return 'The password must be atleast 5 characters long!';
        }
        
        if(strcmp($pass1, $pass2) != 0){
            return 'The passwords do not match!';
        }
        
        if(User::findName($username) != null){
            return 'The username is already in use!';
        }
        
    }
    
    public static function createUser($username, $password){
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, password, hallinto) VALUES (:username, :password, FALSE)');
        $query->execute(array('username' => $username, 'password' => $password));
        
    }

    private static function findName($username) {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE Lower(nimi) = Lower(:username) LIMIT 1');

        $query->execute(array('username' => $username));
        
        $row = $query->fetch();

        $user = null;
        
        if ($row) {
            $user = new User(array(
                'name' => $row['nimi']
            ));
        }

        return $user;
        
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

