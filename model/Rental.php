<?php

require_once 'framework/Model.php';

class Rental extends Model {

    var $id;
    var $user;
    var $book;
    var $rentaldate;
    var $returndate;

    public function __construct($id, $user, $book, $rentaldate, $returndate) {
        $this->id = $id;
        $this->user = $user;
        $this->book = $book;
        $this->rentaldate = $rentaldate;
        $this->returndate = $returndate;
    }
    
    public function insert_book_without_rent(){
        try {
            self::execute("INSERT INTO rental(id,user, book, rentaldate,returndate) VALUES(:user, :book, :rentaldate, :returndate)", array("user" => $this->user, "book" => $this->book,
                "rentaldate" => $this->rentaldate, "returndate" => $this->returndate));
            return $this;
        } catch (Exception $ex) {
            //die("fibi");
//            echo $ex->getTraceAsString(); 

            echo '/////////LIGNE//////////';
            echo $ex->getLine();
            echo '///////msg////////////';
            echo $ex->getMessage();
        }
    }
    
    public static function get_all_rental() {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM rental", array());
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }
        
    
    
    
}
