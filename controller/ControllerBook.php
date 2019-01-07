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
        $pannier = "";
        $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
        $msg = " ";
        $members = User::get_all_user();

        if (isset($_POST["search"])) {
            $value = $_POST["search"];
            $books = Book::get_book_by_critere($value);
        }
        if (empty($_POST["search"]))
            $books = Book::get_all_books();
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }

    // on créé un livre sans img => comme ds msn
    public function add_book() {
        $user = self::get_user_or_redirect();
        $isbn = "";
        $title = "";
        $author = "";
        $editor = "";
        $errors = [];

        if ($user->is_admin() || $user->is_manager())
            if (isset($_POST["createBook"]))
                if (Tools::isset_notEmpty($_POST["isbn"]) && Tools::isset_notEmpty($_POST["author"]) && Tools::isset_notEmpty($_POST["title"]) && Tools::isset_notEmpty($_POST["editor"])) {
                    $isbn = Tools::sanitize($_POST["isbn"]);
                    $title = Tools::sanitize($_POST["title"]);
                    $author = Tools::sanitize($_POST["author"]);
                    $editor = Tools::sanitize($_POST["editor"]);

                    $errors[] = $this->rules_add_book($isbn);
                }
        (new View("add_book"))->show(array("errors" => $errors, "isbn"));
    }

    private function rules_add_book($isbn, $title, $author) {
        $errors = [];
        if (strlen($isbn) !== 13)
            $errors[] = "isbn: incorrect (13)!";
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

//    public function books_manager() {
//        $user = Controller::get_user_or_redirect();
//        $books = Book::get_all_books();
//        var_dump($books);
//    }
//    public function panier() {// a effacer peut-etre 
//        $user = Controller::get_user_or_redirect();
//        if (isset($_POST["idbook"])) {
//            $value = $_POST["idbook"];
//        }
//        (new View("book_detail"))->show(array("books" => $books));
//    }

    public function book_detail() {
        $user = Controller::get_user_or_redirect();
        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
            $book = Book::get_book_by_id($value);
        }
        (new View("book_detail"))->show(array("book" => $book, "profile" => $user));
    }

    public function create_book() {
        $user = Controller::get_user_or_redirect();
        $editbook = "";
        $isbn = "";
        $titre = "";
        $author = "";
        $editor = "";
        $picture = "";
        $test = "test";

        (new View("add_book"))->show(array("editbook" => $editbook, "test" => $test));
    }

    public function edit_book() {
        $user = $this->get_user_or_redirect();
        $book = "";
        $errors = [];
        $success = "";

        if (isset($_POST["editbook"])) {
            $book = Book::get_book_by_id($_POST["editbook"]);
            if (isset($_POST["submitEdit"])) {
                self::update_book_attributes($book);
                $success = "Le bouquin a bien été mis à jour.";
            }
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === self::UPLOAD_ERR_OK) {
            $errors = Book::validate_image($_FILES['image']);
            if ($errors === []) {
                $saveTo = $book->generate_image_name($_FILES['image']);
                $oldFileName = $book->picture;
                if ($oldFileName && file_exists("upload/" . $oldFileName)) {
                    unlink("upload/" . $oldFileName);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], "upload/$saveTo");
                $book->picture = $saveTo;
                $book->update();
                $success = "Le bouquin a bien été mis à jour.";
            }
        }

//        if (isset($_POST["submitEdit"]) && $book !== "") {
//            $book = Book::get_book_by_id($_POST["editbook"]);
//            if (!$book->edit_book())
//                $errors = "Erreur lors de l'édition du bouquin '$book->title' (ISBN: $book->isbn).";
//        }
        (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user, "success" => $success));
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
