<?php

//Olli Kärki

class Game extends BaseModel {

    public $id, $name, $description, $publisher, $publishingdate, $category;
    public static $pagesize = 5;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function validate($date, $description, $name, $publisher){
        if($date == null){
            return 'You must set date!';
        }
        $reviewError = Review::validate($description);
        if(strlen($reviewError) > 0){
            return $reviewError;
        }
        $suggestionError = GameSuggestion::validate($name, $publisher);
        if(strlen($suggestionError) > 0){
            return $suggestionError;
        }
    }
    
    public static function search($search){
        
        $search = strtolower($search);
        
        //kysely
        $query = DB::connection()->prepare("SELECT Peli.id as id, Peli.nimi as nimi, Peli.kuvaus as kuvaus, Peli.julkaisija as julkaisija, Peli.julkaisupaiva as julkaisupaiva, Kategoria.nimi as kategoria FROM Peli LEFT JOIN Kategoria ON Peli.kategoria_id = Kategoria.id "
                . "WHERE Lower(Peli.nimi) like :search OR Lower(Peli.julkaisija) like :search "
                . 'ORDER BY Peli.nimi ASC');
        $query->execute(array(':search' => "%" . $search . "%"));

        $rows = $query->fetchAll();
        $games = array();

        foreach ($rows as $row) { //rivistä olio
            $games[] = new Game(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'publisher' => $row['julkaisija'],
                'publishingdate' => $row['julkaisupaiva'],
                'category' => $row['kategoria']
            ));
        }

        return $games;
        
    }
    
    public static function pages(){
        
        //kysely
        $query = DB::connection()->prepare('SELECT Count(*) AS c FROM Peli');
        $query->execute();
        $row = $query->fetch();
        $count = $row['c'];
        $count = (int)ceil($count / self::$pagesize);
        return $count;
        
    }

    public static function all($search, $page) {
        
        //'limit' => '1', ':offset' => '1'
        if(strcmp($search, 'category') == 0){
            $search = 'Kategoria.nimi';
        } elseif (strcmp($search, 'date') == 0) {
            $search = 'Peli.julkaisupaiva';
        } else {
            $search = 'Peli.nimi';
        }
        
        $limit = self::$pagesize;
        $offset = ($page-1) * $limit;
        if($offset < 0){
            $offset == 0;
        }
        
        //kysely
        $query = DB::connection()->prepare('SELECT Peli.id as id, Peli.nimi as nimi, Peli.kuvaus as kuvaus, Peli.julkaisija as julkaisija, Peli.julkaisupaiva as julkaisupaiva, Kategoria.nimi as kategoria '
                . 'FROM Peli LEFT JOIN Kategoria ON Peli.kategoria_id = Kategoria.id '
                . 'ORDER BY ' . $search . ', Peli.nimi ASC '
                . 'LIMIT :limit OFFSET :offset');
        $query->execute(array('limit' => $limit, 'offset' => $offset));

        $rows = $query->fetchAll();
        $games = array();

        foreach ($rows as $row) { //rivistä olio
            $games[] = new Game(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'publisher' => $row['julkaisija'],
                'publishingdate' => $row['julkaisupaiva'],
                'category' => $row['kategoria']
            ));
        }

        return $games;
    }
    
    public static function save($name, $description, $publisher, $publishingdate, $category){
        
        $query = DB::connection()->prepare('SELECT Count(*) AS count FROM Peli WHERE Lower(nimi) = Lower(:name) AND Lower(julkaisija) = Lower(:publisher)');
        $query->execute(array('name' => $name, 'publisher' => $publisher));
        $count = $query->fetch()['count'];
        
        if($count <= 0){
            
            $query = DB::connection()->prepare('INSERT INTO Peli (nimi, kuvaus, julkaisija, julkaisupaiva, kategoria_id) VALUES(:name, :description, :publisher, :date, :category)');
            $query->execute(array('name' => $name, 'description' => $description, 'publisher' => $publisher, 'date' => $publishingdate, 'category' => $category));
            
            GameSuggestion::removeByString($name, $publisher);
            
        }
        
    }

    public static function find($id) {

        //kysely
        $query = DB::connection()->prepare('SELECT Peli.id as id, Peli.nimi as nimi, Peli.kuvaus as kuvaus, Peli.julkaisija as julkaisija, Peli.julkaisupaiva as julkaisupaiva, Kategoria.nimi as kategoria FROM Peli LEFT JOIN Kategoria ON Peli.kategoria_id = Kategoria.id WHERE Peli.id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        $game = null;
        
        if ($row) {
            $game = new Game(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'publisher' => $row['julkaisija'],
                'publishingdate' => $row['julkaisupaiva'],
                'category' => $row['kategoria']
            ));
        }

        return $game;
    }
    
    public static function update($name, $date, $publisher, $summary, $id, $category){
        
        if(self::find($id) != null){
            
            $query = DB::connection()->prepare('UPDATE Peli SET nimi = :name, kuvaus = :summary, julkaisija = :publisher, julkaisupaiva = :date, kategoria_id = :category WHERE id = :id');
            $query->execute(array('name' => $name, 'summary' => $summary, 'publisher' => $publisher, 'date' => $date, 'id' => $id, 'category' => $category));
            
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
