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
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_book_by_title($title) {
        try {
            $query = self::execute("SELECT * FROM book WHERE title=:title", array("title" => $title));
            $book = $query->fetch();
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"]);
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_book_by_critere($critere) {
        $results = [];
        try {
            $books = self::execute("SELECT * FROM book WHERE title LIKE :critere OR author LIKE :critere OR editor LIKE :critere", array(":critere" => "%" . $critere . "%"));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_book_by_id($id) {
        try {
            $query = self::execute("SELECT * FROM book WHERE id=:id", array("id" => $id));
            $book = $query->fetch();
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"]);
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public function create_book() {
        
    }

    public function update_book($isbn, $title, $author, $editor, $picture) {
        try {
            $query = self::execute("UPDATE book "
                            . "SET isbn = :isbn, title = :title, "
                            . "author = :author, editor = :editor, picture = :picture", array("isbn" => $isbn, "title" => $title, "author" => $author,
                        "editor" => $editor, "picture" => $picture));
        } catch (Exception $ex) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public function edit_picture($user) {
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')
            if ($_FILES['image']['error'] == 0) {
                $error = " ";
                $ok = TRUE;
                $path = $this->save_to($user);
                if ($path == " ") {
                    $ok = FALSE;
                    $error = "Format non-supporté! (gif, jpeg ou png !)";
                }
                if ($ok) {
                    move_uploaded_file($_FILES['image']['tmp_name'], $path);
                }
            } else 
                $error = "Erreur lors du téléchargement du fichier.";
            return $error;
    }

    private function save_to($user) {
        $saveTo = " ";
        if ($_FILES['image']['type'] == "image/gif")
            $saveTo = $user . ".gif";
        else if ($_FILES['image']['type'] == "image/jpeg")
            $saveTo = $user . ".jpg";
        else if ($_FILES['image']['type'] == "image/png")
            $saveTo = $user . ".png";
        return $saveTo;
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

    public function delete_book_rent() {
        try {
            self::execute("DELETE FROM rental WHERE  book=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            $ex->getCode();
            echo "//////////////////////////////";
            echo $ex->getMessage();
            //Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function book_exists() {
        
    }

}
