<?php

require_once 'framework/Model.php';

class Book extends Model {

    var $id;
    var $isbn;
    var $title;
    var $author;
    var $editor;
    var $picture;

    public function __construct($id, $isbn, $title, $author, $editor, $picture) {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->title = $title;
        $this->author = $author;
        $this->editor = $editor;
        $this->picture = $picture;
    }

    public static function get_all_books() {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM book", array());
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"]);
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
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"]);
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_book_by_critere($critere) {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM book "
                            . "WHERE title LIKE :critere OR author LIKE :critere OR "
                            . "editor LIKE :critere", array(":critere" => "%" . $critere . "%"));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"]);
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
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"]);
    }

    private function create() {
        self::execute("INSERT INTO book(isbn, title, author, editor, picture)"
                . "VALUES(:isbn, :title, :author, :editor, :picture)", array("isbn" => $this->isbn, "title" => $this->title,
            "author" => $this->author, "editor" => $this->editor, "picture" => $this->picture));
    }

    public function update() {
        if (self::get_book_by_id($id)) {
            $query = self::execute("UPDATE book SET isbn = :isbn, title = :title, "
                            . "author = :author, editor = :editor, picture = :picture", array("isbn" => $this->isbn, "title" => $this->title, "author" => $this->author,
                        "editor" => $this->editor, "picture" => $this->picture));
        } else {
            $this->create();
        } return $this;
    }

    public function delete_book() {
        try {
            self::execute("DELETE FROM rental WHERE  book=:id", array("id" => $this->id));
            self::execute("DELETE FROM book WHERE  id=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            $ex->getCode();
            echo "//////////////////////////////";
            echo $ex->getMessage();
            //Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function delete_book_rented() {
        try {
            self::execute("DELETE FROM rental WHERE  book=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            $ex->getCode();
            echo "//////////////////////////////";
            echo $ex->getMessage();
            //Tools::abort("Problème lors de l'accès a la base de données");
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

    public function generate_image_name($file) {
        //note : time() est utilisé pour que la nouvelle image n'aie pas
        //       le meme nom afin d'éviter que le navigateur affiche
        //       une ancienne image présente dans le cache
        if ($_FILES['image']['type'] == "image/gif") {
            $saveTo = $this->pseudo . time() . ".gif";
        } else if ($_FILES['image']['type'] == "image/jpeg") {
            $saveTo = $this->pseudo . time() . ".jpg";
        } else if ($_FILES['image']['type'] == "image/png") {
            $saveTo = $this->pseudo . time() . ".png";
        }
        return $saveTo;
    }
}
