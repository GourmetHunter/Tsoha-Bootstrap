<?php

//Olli Kärki

class Game extends BaseModel {

    public $id, $name, $description, $publisher, $publishingdate;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Peli');

        $query->execute();

        $rows = $query->fetchAll();
        $games = array();

        foreach ($rows as $row) { //rivistä olio
            $games[] = new Game(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'publisher' => $row['julkaisija'],
                'publishingdate' => $row['julkaisupaiva']
            ));
        }

        return $games;
    }

    public static function find($id) {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Peli WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        
        $row = $query->fetch();

        if ($row) {
            $game = new Game(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'publisher' => $row['julkaisija'],
                'publishingdate' => $row['julkaisupaiva']
            ));
        }

        return $game;
    }

}
