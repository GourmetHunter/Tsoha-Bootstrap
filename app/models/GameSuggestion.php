<?php

//Olli Kärki

class GameSuggestion extends BaseModel{
    
    public $id, $name, $publisher;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public static function all() {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Peliehdotus');

        $query->execute();

        $rows = $query->fetchAll();
        $suggestions = array();

        foreach ($rows as $row) { //rivistä olio
            $suggestions[] = new Game(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'publisher' => $row['julkaisija']
            ));
        }

        return $suggestions;
        
    }

    public static function find($id) {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Peliehdotus WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        
        $row = $query->fetch();

        if ($row) {
            $suggestion = new Game(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'publisher' => $row['julkaisija']
            ));
        }

        return $suggestion;
    }
    
    
}

