<?php

require_once 'framework/Model.php';
require_once 'model/User.php';
require_once 'model/Book.php';

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

    public function insert_book_without_rent() {
        try {
            self::execute("INSERT INTO rental(id,user, book, rentaldate,returndate) "
                    . "VALUES(:id,:user, :book, :rentaldate, :returndate)", 
                    array("id" => $this->id, "user" => $this->user, "book" => $this->book,
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
            $query = self::execute("SELECT * FROM rental", array());
            $books = $query->fetchAll();
            foreach ($books as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    // faire une requete de jointure 
    public static function get_this_rental($id) {
        $results = [];
        try {
            $query = self::execute("SELECT * FROM rental join book WHERE user=:id", array("id" => $id));
            $books = $query->fetchAll();
            foreach ($books as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_join_book_join_user() {
        try {
            $query = self::execute("SELECT DISTINCT* FROM (rental join user on rental.user=user.id) join book on rental.book=book.id", array());
            $books = $query->fetchAll();
            return $books;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function rent_valid($id) {
        try {
            $query = self::execute("SELECT * FROM rental WHERE user=:id", array("id" => $id));
            $books = $query->fetchAll();
            return 
//            count ($books) >=1 && 
            count($books) <= 5;
            
        } catch (Exception $ex) {
            die("soucis de db");
            echo $ex->getLine();
            echo '/////';
            echo $ex->getMessage();
        }
    }

    public static function get_rental_by_id_book($id) {
       $results=[];
        try {
            $query = self::execute("SELECT * FROM rental WHERE book=:id", array("id" => $id));
            $books = $query->fetchAll();
              foreach ($books as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }
    
    public static function get_rentals_by_user($user) {
        $res = [];
        try{
            $query = self::execute("SELECT * FROM rental "
                                 . "WHERE user = :user AND rentaldate != NULL", 
                                    array("user" => $user));
            $rentals = $query->fetchAll();
            foreach ($rentals as $rental)
                $res[] = new Rental ($rental["id"], $rental["user"], $rental["book"], $rental["rentaldate"], $rental["returndate"]);
            return $res;
        } catch (Exception $ex) {
            //Tools::abort("Problème lors de l'accès à la base de données.");
            $ex->getMessage();
        }
    }
    
    
    // methode pas a la bonne place
    public function get_book() {
        try{
            $query = self::execute("SELECT * FROM book WHERE id = :id", array("id" => $this->book));
            $book = $query->fetch();
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"]);
            } catch (Exception $ex) {
            abort("Problème lors de l'accès a la base de données");
        }
    }
    
    public function delete_rental() {
        try {
            self::execute("DELETE FROM rental WHERE  id=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }
    
    public static function is_already_rented($book,$user) {
        try {
            $query = self::execute("SELECT * FROM rental WHERE book=:book AND user=:user", array("book" => $book,"user"=>$user));
            $books = $query->fetchAll();
            return count($books) != 0;
        } catch (Exception $ex) {
            die("Soucis de db");
        }
    }
    
     public function update_rental_rentdate($rentaldate) {
         self::execute("UPDATE rental SET rentaldate = :rentaldate WHERE id=:id  ", array("rentaldate"=>$rentaldate,"id"=> $this->id));
               
    
}
}