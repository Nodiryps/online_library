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
            self::execute("INSERT INTO rental(id,user, book, rentaldate,returndate) VALUES(:id,:user, :book, :rentaldate, :returndate)", array("id" => $this->id, "user" => $this->user, "book" => $this->book,
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

    // faire une requete de jointure
    public static function get_this_rental($id) {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM rental join book WHERE user=:id", array("id" => $id));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_join_book_join_user() {
        $results = [];
        try {
            $books = self::execute("select * FROM (rental join user on rental.user=user.id) join book on rental.book=book.id", array());
            $query = $books->fetchAll();
            return $query;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function rent_valid($id) {
        try {
            $books = self::execute("select * FROM rental where user=:id", array("id" => $id));
            $query = $books->fetchAll();

            return count($query) <= 4;
        } catch (Exception $ex) {
            die("soucis de db");
        }
    }

    public static function get_rental_by_id($id) {
       $results=[];
        try {
            $books = self::execute("SELECT * FROM rental WHERE book=:id", array("id" => $id));
            $query = $books->fetchAll();
              foreach ($query as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function delete_rental() {
        try {
            self::execute("DELETE FROM rental WHERE  id=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

}
