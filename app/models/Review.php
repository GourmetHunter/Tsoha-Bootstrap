<?php

//Olli Kärki

class Review extends BaseModel{
    
    public $id, $user, $score, $content, $date;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public static function findByGame($id) {
        
        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Arvostelu LEFT JOIN Kayttaja ON Arvostelu.kayttaja_id = kayttaja.id WHERE Arvostelu.peli_id = :id');

        $query->execute(array('id' => $id));

        $rows = $query->fetchAll();
        $reviews = array();

        foreach ($rows as $row) { //rivistä olio
            $reviews[] = new Review(array(
                'id' => $row['id'],
                'user' => $row['nimi'],
                'score' => $row['pisteet'],
                'content' => $row['sisältö'],
                'date' => $row['paivays']
            ));
        }

        return $reviews;
        
    }
    
    public static function score($id){
        
        $query = DB::connection()->prepare('SELECT Avg(pisteet) AS score From Arvostelu WHERE peli_id = :id');
        $query->execute(array('id' => $id));
        $score = $query->fetch()['score'];
        
        return $score;
        
    }
    
    public static function delete($id) {
        
        $query = DB::connection()->prepare('DELETE FROM Arvostelu WHERE id = :id');
        $query->execute(array('id' => $id));
        
    }
    
    public static function saveorupdate($id, $userid, $score, $content) {
        
        $query = DB::connection()->prepare('SELECT Count(*) AS count FROM Arvostelu WHERE peli_id = :id AND kayttaja_id = :userid');
        $query->execute(array('id' => $id, 'userid' => $userid));
        $count = $query->fetch()['count'];
        
        if($count <= 0){
            
            $query = DB::connection()->prepare('INSERT INTO Arvostelu (kayttaja_id, peli_id, pisteet, sisältö, paivays) VALUES('
                    . ':userid,'
                    . ':id,'
                    . ':score,'
                    . ':content,'
                    . 'Now())');
            
            $query->execute(array('userid' => $userid, 'id' => $id, 'score' => $score, 'content' => $content));
            
        } else {
            
            $query = DB::connection()->prepare('UPDATE Arvostelu Set pisteet = :score, sisältö = :content, paivays = Now() WHERE kayttaja_id = :userid AND peli_id = :id');       
            $query->execute(array('userid' => $userid, 'id' => $id, 'score' => $score, 'content' => $content));
            
        }
        
    }
    
    
}

