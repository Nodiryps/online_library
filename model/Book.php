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
                            . "WHERE isbn LIKE :critere OR title LIKE :critere OR author LIKE :critere OR "
                            . "editor LIKE :critere AND book.id  not in (select rental.book from rental) " ,array(":critere" => "%" . $critere . "%"));
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
            self::execute("UPDATE book SET picture=:picture  WHERE id=:id", array("picture" => NULL, "id" => $this->id));
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

//    private static function titleOk($string) {
//        $res = preg_replace('~[\\\\/.,;:*!?&@{}"<>|]~', '', $string);
//        $res = str_replace('é', 'e', $res);
//        $res = str_replace('è', 'e', $res);
//        $res = str_replace('à', 'a', $res);
//        $res = str_replace('ê', 'e', $res);
//        $res = str_replace(' ', '', $res);
//        return $res;
//    }

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

    private function get_nbCopie() {
        try {
            $query = self::execute("SELECT nbCopies FROM book where id=:id", array("id" => $this->id));
            $nb = $query->fetch();
            return intval($nb[0]);
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    private function get_nbCopie_from_rental() {
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

    public static function set_book_attr_add(&$isbn, &$title, &$author, &$editor, &$nbcopies) {
        $isbn = $_POST["isbn"];
        $title = $_POST["title"];
        $author = $_POST["author"];
        $editor = $_POST["editor"];
        $nbcopies = $_POST["nbCopie"];
    }

    public static function set_book_attr_edit(&$book) {
        if ($_POST['idbook'] !== "")
            $book = Book::get_book_by_id($_POST['idbook']);
        if ($_POST['isbn'] !== "")
            $book->isbn = self::isbn_format_string($_POST['isbn']);
        if ($_POST['title'] !== "")
            $book->title = $_POST['title'];
        if ($_POST['author'] !== "")
            $book->author = $_POST['author'];
        if ($_POST['editor'] !== "")
            $book->editor = $_POST['editor'];
        if ($_POST['nbCopie'] !== "")
            $book->nbCopies = $_POST["nbCopie"];
    }

    private static function isbn_format_string($isbn) {
        return str_replace('-', '', $isbn);
    }

    public function add_picture($picture_path, $title) {
        $infosfichier = pathinfo($_FILES['picture']['name']);
        $extension_upload = $infosfichier['extension'];
        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'PNG', 'png', 'JPG', 'JPEG', 'GIF');
        if (in_array($extension_upload, $extensions_autorisees)) {
//            $titleOk = self::titleOk($title); // remplace les char par '' dans $title
            $picture_path = $title . "." . $extension_upload;
            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
        }
        return $picture_path;
    }

    public function edit_picture() {
        $infosfichier = pathinfo($_FILES['picture']['name']);
        $extension_upload = $infosfichier['extension'];
        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'PNG', 'png', 'JPG', 'JPEG', 'GIF');
        if (in_array($extension_upload, $extensions_autorisees)) {
//            $titleOk = self::titleOk($this->title); // remplace les char par '' dans $title
            $picture_path = $this->title . "." . $extension_upload;
            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
            $this->picture = $picture_path;
        }
    }

    public static function isbn_format_EAN_13($isbn) {
        return substr($isbn, 0, 3) . "-" . substr($isbn, 3, 1) . "-"
                . substr($isbn, 4, 4) . "-" . substr($isbn, 8, 4) . "-" . substr($isbn, 12, 1);
    }

    public static function calcul_isbn($isbn) {
        $total = 0;
        $rest = 0;
        $isbn = str_replace(' ', '', $isbn);
        $tabIsbn = str_split($isbn, 1);
        self::one_or_three($tabIsbn);
        $total = self::addition_isbn($tabIsbn);
        if ($total % 10 != 0) {
            $rest = (int) 10 - (int) ($total % 10);
        }
        return $isbn . $rest;
    }
    
    private static function one_or_three(&$tabIsbn) {
        for ($i = 1; $i <= sizeof($tabIsbn); ++$i) {
            if ($i % 2 == 0) {
                $tabIsbn[$i - 1] *= 3;
            }
            if ($i % 2 != 0) {
                $tabIsbn[$i - 1] *= 1;
            }
        }
    }

    private static function addition_isbn($isbn) {
        $res = 0;
        foreach ($isbn as $s) {
            $res += $s;
        }
        return $res;
    }

}
