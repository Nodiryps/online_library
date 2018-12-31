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
            $books = self::execute("SELECT * FROM book WHERE isbn LIKE :critere OR title LIKE :critere OR author LIKE :critere OR editor LIKE :critere", array(":critere" => "%" . $critere . "%"));
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

    public function edit_book($bookId) {
        if (isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '')
            if ($_FILES['picture']['error'] == 0) {
                $path = $this->is_path_ok($bookId);
                if ($path !== " ") {
                    move_uploaded_file($_FILES['picture']['tmp_name'], $path);
                    $this->update($isbn, $title, $author, $editor, $path);
                    return TRUE;
                } else 
                    return FALSE;
            } else
                return FALSE;
    }
    
    private function update($isbn, $title, $author, $editor, $picture) {
        try {
            $query = self::execute("UPDATE book "
                            . "SET isbn = :isbn, title = :title, "
                            . "author = :author, editor = :editor, picture = :picture", array("isbn" => $isbn, "title" => $title, "author" => $author,
                        "editor" => $editor, "picture" => $picture));
            return TRUE;
        } catch (Exception $ex) {
            abort("Problème lors de l'accès a la base de données");
            return FALSE;
        }
    }

    private function is_path_ok($bookId) {
        $path = " ";
        if ($_FILES['picture']['type'] == "picture/gif")
            $path = $bookId . ".gif";
        else if ($_FILES['picture']['type'] == "picture/jpeg")
            $path = $bookId . ".jpg";
        else if ($_FILES['picture']['type'] == "picture/png")
            $path = $bookId . ".png";
        return $path;
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
