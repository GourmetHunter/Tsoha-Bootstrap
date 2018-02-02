<?php

//Olli Kärki

class GameSuggestion extends BaseModel {

    public $id, $name, $publisher, $suggested;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function suggest($title, $publisher, $userid) {

        $query = DB::connection()->prepare('SELECT Count(*) AS count FROM Peliehdotus WHERE Lower(nimi) = Lower(:title) AND Lower(julkaisija) = Lower(:publisher)');
        $query->execute(array('title' => $title, 'publisher' => $publisher));

        $count = $query->fetch()['count'];

        if ($count <= 0) {

            $query = DB::connection()->prepare('INSERT INTO Peliehdotus (nimi, julkaisija) VALUES(:title, :publisher)');
            $query->execute(array('title' => $title, 'publisher' => $publisher));
        }

        $query = DB::connection()->prepare('SELECT id AS id FROM Peliehdotus WHERE Lower(nimi) = Lower(:title) AND Lower(julkaisija) = Lower(:publisher) LIMIT 1');
        $query->execute(array('title' => $title, 'publisher' => $publisher));

        $gameid = $query->fetch()['id'];

        $query = DB::connection()->prepare('SELECT Count(*) AS count FROM Peliehdotus_kayttaja WHERE kayttaja_id = :userid AND peliehdotus_id = :gameid');
        $query->execute(array('userid' => $userid, 'gameid' => $gameid));

        $count = $query->fetch()['count'];

        if ($count <= 0) {

            $query = DB::connection()->prepare('INSERT INTO Peliehdotus_kayttaja (kayttaja_id, peliehdotus_id, paivays) VALUES(:userid, :gameid, Now())');
            $query->execute(array('userid' => $userid, 'gameid' => $gameid));
            
        }
        
    }

    public static function all() {

        //kysely
        $query = DB::connection()->prepare('SELECT Peliehdotus.id AS id, Peliehdotus.nimi AS name, Peliehdotus.julkaisija AS publisher, COUNT(*) AS suggested FROM Peliehdotus LEFT JOIN Peliehdotus_kayttaja ON Peliehdotus.id = Peliehdotus_kayttaja.peliehdotus_id GROUP BY Peliehdotus.id');

        $query->execute();

        $rows = $query->fetchAll();
        $suggestions = array();

        foreach ($rows as $row) { //rivistä olio
            $suggestions[] = new GameSuggestion(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'publisher' => $row['publisher'],
                'suggested' => $row['suggested'],
            ));
        }

        return $suggestions;
    }

    public static function remove($accountid, $suggestionid) {


        $query = DB::connection()->prepare('DELETE FROM Peliehdotus_kayttaja WHERE kayttaja_id = :accountid AND peliehdotus_id = :suggestionid');
        $query->execute(array('accountid' => $accountid, 'suggestionid' => $suggestionid));

        $query = DB::connection()->prepare('SELECT Count(*) AS count FROM Peliehdotus_kayttaja WHERE peliehdotus_id = :suggestionid');
        $query->execute(array('suggestionid' => $suggestionid));

        $row = $query->fetch();

        if ($row['count'] <= 0) {
            $query = DB::connection()->prepare('DELETE FROM Peliehdotus WHERE id = :suggestionid');
            $query->execute(array('suggestionid' => $suggestionid));
        }
    }

    public static function removeByString($name, $publisher) {
        
        $query = DB::connection()->prepare('SELECT id AS id FROM Peliehdotus WHERE Lower(nimi) = Lower(:name) AND Lower(julkaisija) = Lower(:publisher) LIMIT 1');
        $query->execute(array('name' => $name, 'publisher' => $publisher));
        $id = $query->fetch();

        if($id != null){
            $id = $id['id'];
            
            $query = DB::connection()->prepare('DELETE FROM Peliehdotus_kayttaja WHERE peliehdotus_id = :id');
            $query->execute(array('id' => $id));
            
            $query = DB::connection()->prepare('DELETE FROM Peliehdotus WHERE id = :id');
            $query->execute(array('id' => $id));
            
        }
        
    }

    public static function findByUser($identifier) {

        //kysely
        $query = DB::connection()->prepare('SELECT Peliehdotus.id AS id, '
                . 'Peliehdotus.nimi AS name, '
                . 'Peliehdotus.julkaisija AS publisher, '
                . 'Peliehdotus_kayttaja.paivays AS date '
                . 'FROM Peliehdotus LEFT JOIN Peliehdotus_kayttaja '
                . 'ON Peliehdotus.id = Peliehdotus_kayttaja.peliehdotus_id '
                . 'WHERE Peliehdotus_kayttaja.kayttaja_id = :identifier '
                . 'GROUP BY Peliehdotus_kayttaja.kayttaja_id, Peliehdotus.id, Peliehdotus_kayttaja.paivays');

        $query->execute(array('identifier' => $identifier));

        $rows = $query->fetchAll();
        $suggestions = array();

        foreach ($rows as $row) { //rivistä olio
            $suggestions[] = new GameSuggestion(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'publisher' => $row['publisher'],
                'date' => $row['date'],
            ));
        }

        return $suggestions;
    }

    public static function find($id) {

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Peliehdotus WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        $suggestion = null;
        
        if ($row) {
            $suggestion = new GameSuggestion(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'publisher' => $row['julkaisija']
            ));
        }

        return $suggestion;
    }

}
