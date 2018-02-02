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
    
    public static function save($name, $description, $publisher, $publishingdate){
        
        $query = DB::connection()->prepare('SELECT Count(*) AS count FROM Peli WHERE Lower(nimi) = Lower(:name) AND Lower(julkaisija) = Lower(:publisher)');
        $query->execute(array('name' => $name, 'publisher' => $publisher));
        $count = $query->fetch()['count'];
        
        if($count <= 0){
            
            $query = DB::connection()->prepare('INSERT INTO Peli (nimi, kuvaus, julkaisija, julkaisupaiva) VALUES(:name, :description, :publisher, :date)');
            $query->execute(array('name' => $name, 'description' => $description, 'publisher' => $publisher, 'date' => $publishingdate));
            
            GameSuggestion::removeByString($name, $publisher);
            
        }
        
    }

    public static function find($id) {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Peli WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        $game = null;
        
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
    
    public static function update($name, $date, $publisher, $summary, $id){
        
        if(self::find($id) != null){
            
            $query = DB::connection()->prepare('UPDATE Peli SET nimi = :name, kuvaus = :summary, julkaisija = :publisher, julkaisupaiva = :date WHERE id = :id');
            $query->execute(array('name' => $name, 'summary' => $summary, 'publisher' => $publisher, 'date' => $date, 'id' => $id));
            
        }
        
    }
    
    public static function delete($id){
        
        
        
        if(self::find($id) != null){
            
            $query = DB::connection()->prepare('DELETE FROM Arvostelu WHERE peli_id = :id');
            $query->execute(array('id' => $id));
            
            $query = DB::connection()->prepare('DELETE FROM Peli WHERE id = :id');
            $query->execute(array('id' => $id));
            
        }
        
    }

}
