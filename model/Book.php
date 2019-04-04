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
            $books = self::execute("SELECT * FROM book where book.id  not in (select rental.book from rental where user=:id)  ", array("id" => $id));
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

    public static function add_picture($title, $picture_path) {
        if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
            if ($_FILES['picture']['error'] == 0) {
                $infosfichier = pathinfo($_FILES['picture']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array($extension_upload, $extensions_autorisees)) {
                    $titleOk = self::titleOk($title); // remplace les char par '' dans $title
                    $picture_path = $titleOk . "." . $extension_upload;
                    move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                }
            }
        } return $picture_path;
    }

    public static function titleOk($string) {
        $res = preg_replace('~[\\\\/.,;:*!?&@{}"<>|]~', '', $string);
        $res = preg_replace('~[\\éè]~', 'e', $res);
        $res = preg_replace('~[\\à]~', 'a', $res);
        return $res;
    }

    public static function delete_img($book, $bookpicToDel, $errors, $user, $nbCopie) {
        if (isset($_POST["delimageH"])) {
            $edit = $_POST["delimageH"];
            $bookpicToDel = Book::get_book_by_id($edit);
            if ($bookpicToDel->picture !== NULL) {
                unlink("uploads/" . $bookpicToDel->picture);
                $bookpicToDel->delete_image();
            }
            $book = Book::get_book_by_id($edit);
            $book->isbn = substr($book->isbn, 0, 12);
            (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user, "nbCopie" => $nbCopie)); // pour "refresh" l'img suppr
        }
    }

    public static function rules_add_book($isbn, $title, $author, $editor, $nbCopie) {
        $errors = [];
        if (empty(trim($isbn)) || strlen($isbn) !== 12)
            $errors[] = "isbn: isbn incorrect (12 chiffres)!";
        if (empty(trim($title)) || strlen($title) < 2)
            $errors[] = "titre: trop court (2 min.)!";
        if (empty(trim($author)) || strlen($author) < 5)
            $errors[] = "auteur.e: trop court (5 min.)!";
        if (empty(trim($editor)) || strlen($editor) < 2)
            $errors[] = "édition: trop court (2 min.)!";
        if ($nbCopie < 0)
            $errors[] = " le nombre de copies ne doit pas etre inferieur a Zero!";
        return $errors;
    }

    public function get_nbCopie() {
        try {
            $query = self::execute("SELECT nbCopies FROM book where id=:id", array("id" => $this->id));
            $nb = $query->fetch();
            return intval($nb[0]);
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public function get_nbCopie_from_rental() {
        try {
            $query = self::execute("SELECT count(*) FROM rental where book=:id", array("id" => $this->id));
            $nb = $query->fetch();
            return intval($nb[0]);
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public function nbCopies_to_display() {
        return $this->get_nbCopie() - $this->get_nbCopie_from_rental();
    }

}
