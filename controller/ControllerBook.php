<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'model/Rental.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerBook extends Controller {

    const UPLOAD_ERR_OK = 0;

    public function index() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
        $msg = " ";
        $members = User::get_all_user();
//        $test=Rental::get_rental_count();
//        var_dump($test);
        $test = Rental::rent_valid($user->id);


        if (isset($_POST["search"])) {
            $value = $_POST["search"];
            $books = Book::get_book_by_critere($value);
            $msg = " ";
        }
        if (empty($_POST["search"]))
            $books = Book::get_all_books();

        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "test" => $test));
    }

// on créé un livre sans img => comme ds msn
    public function add_book() {
        $user = self::get_user_or_redirect();
        $isbn = "";
        $title = "";
        $author = "";
        $editor = "";
        $errors = [];
        $picture_path="";

        if (isset($_POST["isbn"]) && isset($_POST["author"]) && isset($_POST["title"]) && isset($_POST["editor"])) {
            $isbn = Tools::sanitize($_POST["isbn"]);
            $title = Tools::sanitize($_POST["title"]);
            $author = Tools::sanitize($_POST["author"]);
            $editor = Tools::sanitize($_POST["editor"]);

            $errors = $this->rules_add_book($isbn, $title, $author);
            //var_dump($_FILES);

            if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                if ($_FILES['picture']['error'] == 0) {
                    $infosfichier = pathinfo($_FILES['picture']['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension_upload, $extensions_autorisees)) {
                        $picture_path=$title.".".$extension_upload;
                        move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' .$picture_path);
                    }
                }
            }


            if (empty($errors)) {
                $book = new Book(0, $isbn, $title, $author, $editor, $picture_path);
                $book->create();
            }
        }
        (new View("add_book"))->show(array("errors" => $errors, "isbn"));
    }

    private function rules_add_book($isbn, $title, $author) {
        $errors = [];
        if (strlen($isbn) !== 13)
            $errors[] = "isbn: isbn incorrect (13 chiffres)!";
        if (strlen($title) < 2)
            $errors[] = "titre: trop court (2 min.)!";
        if (strlen($author) < 5)
            $errors[] = "auteur.e: trop court (5 min.)!";
        if (strlen($author) < 2)
            $errors[] = "édition: trop court (2 min.)!";
        return $errors;
    }

    public function delete_book() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        $msg = " ";
        $members = User::get_all_user();
        if (isset($_POST["delbook"])) {
            $value = $_POST["delbook"];
            $delbook = Book::get_book_by_id($value);
            $delbook->delete_book();
            $books = Book::get_all_books();
            $getUserRental = $user->get_rental_join_book_join_user_by_user();
        }

        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }

    public function book_detail() {
        $user = Controller::get_user_or_redirect();
        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
            $book = Book::get_book_by_id($value);
        }
        (new View("book_detail"))->show(array("book" => $book, "profile" => $user));
    }

    public function edit_book() {
        $user = $this->get_user_or_redirect();
        $book = "";
        $errors = [];
        $success = "";
        $id = "";
        $isbn = "";
        $title = "";
        $author = "";
        $editor = "";
        $picture = "";
        if (isset($_POST['editbook'])) {
            $book = Book::get_book_by_id($_POST['editbook']);
//            $id = $book->id;
//            $isbn = $book->isbn;
//            $title = $book->title;
//            $author = $book->author;
//            $editor = $book->editor;
//            $picture = $book->picture;
        }
        if (isset($_POST['idbook']) && isset($_POST['isbn']) && isset($_POST['title']) && isset($_POST['editor']) && isset($_POST['author'])) {
            $id = $_POST['idbook'];
            $book = Book::get_book_by_id($id);
            $isbn = $book->isbn;
            $title = $book->title;
            $author = $book->author;
            $editor = $book->editor;

            if (isset($_POST['isbn']) && isset($_POST['isbn']) !== "")
                $book->isbn = $this->isbn_format_string(Tools::sanitize($_POST["isbn"]));
            if (isset($_POST['title']) && isset($_POST['title']) !== "")
                $book->title = Tools::sanitize($_POST['title']);
            if (isset($_POST['author']) && isset($_POST['author']) !== "")
                $book->author = Tools::sanitize($_POST['author']);
            if (isset($_POST['editor']) && isset($_POST['editor']) !== "")
                $book->editor = Tools::sanitize($_POST["editor"]);
          

            $errors = $this->rules_add_book($isbn, $title, $author);
           // $errors = Book::validate_photo($book->picture);
            //self::add_image($book);
            
             if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                if ($_FILES['picture']['error'] == 0) {
                    $infosfichier = pathinfo($_FILES['picture']['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension_upload, $extensions_autorisees)) {
                        $picture_path=$title.".".$extension_upload;
                        move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' .$picture_path);
                    }
                }
            }

            if (empty($error)) {
                $book->update_book();
                $this->redirect("book", "index");
            }
        }

        (new View("edit_book"))->show(array("book" => $book, "id" => $id, "isbn" => $isbn, "title" => $title, "author" => $author, "editor" => $editor, "picture" => $picture, "errors" => $errors, "profile" => $user, "success" => $success));
    }

    public static function add_image($book) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === self::UPLOAD_ERR_OK) {
            echo " salut";
            $errors = Book::validate_photo($_FILES['image']);
            if (empty($errors)) {
                $saveTo = $book->validate_image($_FILES['image']);
                $oldFileName = $member->picture_path;
                if ($oldFileName && file_exists("upload/" . $oldFileName)) {
                    unlink("upload/" . $oldFileName);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], "upload/$saveTo");
                $member->picture_path = $saveTo;
                $member->update();
                $success = "Your profile has been successfully updated.";
            }
        }
    }

    public static function isbn_format_EAN_13($isbn) {
        return substr($isbn, 0, 3) . "-" . substr($isbn, 3, 1) . "-"
                . substr($isbn, 4, 4) . "-" . substr($isbn, 8, 4) . "-" . substr($isbn, 12, 1);
    }

    public static function isbn_format_string($isbn) {
        return str_replace('-', '', $isbn);
    }

    private function validate_book($isbn, $title, $author, $editor) {
        // à completer
    }

    private static function update_book_attributes($book) {
        $book->isbn = self::receives($_POST["isbn"]);
        $book->author = self::receives($_POST["author"]);
        $book->title = self::receives($_POST["title"]);
        $book->editor = self::receives($_POST["editor"]);
        $book->update();
    }

    private static function receives($value) {
        $post = $_POST[$value];
        if (isset($post))
            return $post;
    }

}
