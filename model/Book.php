<?php

require_once 'framework/Model.php';
require_once 'Model/Rental.php';

class Book extends Model {

    var $id;
    var $isbn;
    var $title;
    var $author;
    var $editor;
    var $picture;
    var $nbCopies;

    public function __construct($id, $isbn, $title, $author, $editor, $picture, $nbCopies) {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->title = $title;
        $this->author = $author;
        $this->editor = $editor;
        $this->picture = $picture;
        $this->nbCopies = $nbCopies;
    }

    public static function get_all_books($id) {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM book where book.id  not in (select rental.book from rental where user=:id)  ", array("id"=>$id));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"], $row["nbCopies"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_all_books_join_rental() {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM book join rental where book.id=rental.book ", array());
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"], $row["nbCopies"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_book_by_title($title) {
        try {
            $query = self::execute("SELECT * FROM book WHERE title=:title", array("title" => $title));
            $book = $query->fetch();
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"], $book["nbCopies"]);
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_book_by_critere($critere) {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM book "
                            . "WHERE book.id  not in (select rental.book from rental) "
                            . "AND (isbn LIKE :critere OR title LIKE :critere OR author LIKE :critere OR "
                            . "editor LIKE :critere)", array(":critere" => "%" . $critere . "%"));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"], $row["nbCopies"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_book_by_id($id) {
        $query = self::execute("SELECT * FROM book WHERE id=:id", array("id" => $id));
        $book = $query->fetch();
        if ($query->rowCount() == 0)
            return false;
        else
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"], $book["nbCopies"]);
    }

    public function create() {
        self::execute("INSERT INTO book(isbn, title, author, editor, picture,nbCopies)"
                . "VALUES(:isbn, :title, :author, :editor, :picture,:nbCopies)", array("isbn" => $this->isbn, "title" => $this->title,
            "author" => $this->author, "editor" => $this->editor, "picture" => $this->picture, "nbCopies" => $this->nbCopies));
    }

    public function update() {
        if (self::get_book_by_id($this->id)) {
            $query = self::execute("UPDATE book SET isbn = :isbn, title = :title, "
                            . "author = :author, editor = :editor, picture = :picture, nbCopies=:nbCopie WHERE id=:id", array("isbn" => $this->isbn, "title" => $this->title, "author" => $this->author,
                        "editor" => $this->editor, "picture" => $this->picture, "id" => $this->id, "nbCopie" => $this->nbCopies));
        }
    }

    public function update_nbCopies() {
        if (self::get_book_by_id($this->id)) {
            $query = self::execute("UPDATE book SET nbCopies = :nbCopies,"
                            . "WHERE id=:id", array("nbCopies" => $this->nbCopies));
        }
    }

    public function delete_book() {
        try {
            self::execute("DELETE FROM rental WHERE  book=:id", array("id" => $this->id));
            self::execute("DELETE FROM book WHERE  id=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function delete_book_rented() {
        try {
            self::execute("DELETE FROM rental WHERE  book=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function delete_image() {
        try {
            self::execute("UPDATE book SET picture=:picture  WHERE id=:id", array("picture" => null, "id" => $this->id));
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function validate_image($file) {// mettre la fonction dans controller
        $errors = [];
        if (isset($file['name']) && $file['name'] != '') {
            if ($file['error'] == 0) {
                $valid_types = array("image/gif", "image/jpeg", "image/png");
                if (!in_array($_FILES['image']['type'], $valid_types)) {
                    $errors[] = "Formats supportés: gif, jpg/jpeg or png.";
                }
            } else {
                $errors[] = "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }
        return $errors;
    }

    public function validate_image_type($file) {
        //note : time() est utilisé pour que la nouvelle image n'aie pas
        //       le meme nom afin d'éviter que le navigateur affiche
        //       une ancienne image présente dans le cache
        if ($_FILES['image']['type'] == "image/gif") {
            $saveTo = $this->title . time() . ".gif";
        } else if ($_FILES['image']['type'] == "image/jpeg") {
            $saveTo = $this->title . time() . ".jpg";
        } else if ($_FILES['image']['type'] == "image/png") {
            $saveTo = $this->title . time() . ".png";
        }
        return $saveTo;
    }

    public static function validate_photo($file) {
        $errors = [];
        if (isset($file['name']) && $file['name'] != '') {
            if ($file['error'] == 0) {
                $valid_types = array("image/gif", "image/jpeg", "image/png");
                if (!in_array($_FILES['image']['type'], $valid_types)) {
                    $errors[] = "Unsupported image format : gif, jpg/jpeg or png.";
                }
            } else {
                $errors[] = "Error while uploading file.";
            }
        }
        return $errors;
    }

    public static function get_title_by_id($id) {
        try {
            $query = self::execute("SELECT title FROM book WHERE id=:id", array("id" => $id));
            $book = $query->fetch();
            return $book[0];
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public static function get_author_by_id($id) {
        try {
            $query = self::execute("SELECT author FROM book WHERE id=:id", array("id" => $id));
            $book = $query->fetch();
            return $book[0];
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public function nbCopies_of_a_book() {
        try {
            $query = self::execute("SELECT COUNT(*) FROM rental WHERE book=:id AND rentaldate IS NOT NULL", array("id" => $this->id));
            $books = $query->fetchAll();
            return $books[0][0];
        } catch (Exception $ex) {
            Tools::abort("problemes lors de l'acces a la DB");
        }
    }

    public static function existIsbn($isbn) {
        try {
            $query = self::execute("SELECT * FROM book  WHERE isbn=:isbn", array("isbn" => $isbn));
            $books = $query->fetchAll();
            if (sizeof($books) == 0)
                return false;
        } catch (Exception $ex) {
            Tools::abort("problemes lors de l'acces a la DB");
        }
    }
    
      public function titleOk($string) {
        $res = preg_replace('~[\\\\/.,;:*!?&@{}"<>|]~', '', $string);
        $res = preg_replace('~[\\éè]~', 'e', $res);
        $res = preg_replace('~[\\à]~', 'a', $res);
        return $res;
    }
    
    public static function rules_add_book($isbn, $title, $author, $editor,$nbCopie) {
        $errors = [];
        if (empty(trim($isbn)) || empty(trim($title)) || empty(trim($author)) || empty(trim($editor)))
            $errors[] = "TOUS les champs sont obligatoires !";

        if (strlen($isbn) !== 13)
            $errors[] = "isbn: isbn incorrect (13 chiffres)!";
        if (strlen($title) < 2)
            $errors[] = "titre: trop court (2 min.)!";
        if (strlen($author) < 5)
            $errors[] = "auteur.e: trop court (5 min.)!";
        if (strlen($editor) < 2)
            $errors[] = "édition: trop court (2 min.)!";
        if($nbCopie <0)
            $errors[]=" le nombre de copies de doit pas etre inferieur a Zero!";
        
        return $errors;
    }
    
    public  function get_nbCopie() {
        try {
            $query = self::execute("SELECT nbCopies FROM book where id=:id", array("id" => $this->id));
            $nb = $query->fetch();
            return $nb[0];
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

}