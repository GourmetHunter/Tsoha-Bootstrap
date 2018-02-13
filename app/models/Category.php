<?php

//Olli Kärki

class Category extends BaseModel{
    
    public $id, $name;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    public static function all(){
        
        $query = DB::connection()->prepare('SELECT * FROM Kategoria');
        $query->execute();

        $rows = $query->fetchAll();
        $categories = array();

        foreach ($rows as $row) { //rivistä olio
            $categories[] = new Category(array(
                'id' => $row['id'],
                'name' => $row['nimi']
            ));
        }

        return $categories;
        
    }
    
    public static function getId($name){

        //kysely
        $query = DB::connection()->prepare('SELECT * FROM Kategoria WHERE nimi = :name LIMIT 1');
        $query->execute(array('name' => $name));
        $row = $query->fetch();

        $category = null;
        
        if ($row) {
            $category = new Category(array(
                'id' => $row['id'],
                'name' => $row['nimi']
            ));
        }

        return $category;
        
    }
    
}

