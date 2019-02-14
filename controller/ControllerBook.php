<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'model/Rental.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerBook extends Controller {

    public function index() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books();
        $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
        $members = User::get_all_user();
        $usertoAddRent = $user;

        if (isset($_POST["search"])) {
            $value = $_POST["search"];
            $books = Book::get_book_by_critere($value);
        }
        if (empty($_POST["search"]))
            $books = Book::get_all_books();

        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "members" => $members, "actualpanier" => $usertoAddRent));
    }

// on créé un livre sans img => comme ds msn
    public function add_book() {
        $user = self::get_user_or_redirect();
        $isbn = "";
        $title = "";
        $author = "";
        $editor = "";
        $errors = [];
        $picture_path = "";

        if (isset($_POST["isbn"]) && isset($_POST["author"]) && isset($_POST["title"]) && isset($_POST["editor"])) {
            $isbn = $_POST["isbn"];
            $title = $_POST["title"];
            $author = $_POST["author"];
            $editor = $_POST["editor"];
            $errors = $this->rules_add_book($isbn, $title, $author);
            if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                if ($_FILES['picture']['error'] == 0) {
                    $infosfichier = pathinfo($_FILES['picture']['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension_upload, $extensions_autorisees)) {
                        $titleOk = preg_replace('~[\\\\/.,;:*!?"<>|]~', '', $title); // remplace les char par '' dans $title
                        $picture_path = $titleOk . "." . $extension_upload;
                        move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                    }
                }
            }
//            $picture_path = $this->add_picture($title, $picture_path);

            if (empty($errors)) {
                $book = new Book(0, $isbn, $title, $author, $editor, $picture_path);
                $book->create();
                if (isset($_POST["idbook"]))
                    $this->redirect("book", "index");
            }
        } (new View("add_book"))->show(array("errors" => $errors, "isbn"));
    }

    private function add_picture($title, $picture_path) {
        if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
            if ($_FILES['picture']['error'] == 0) {
                $infosfichier = pathinfo($_FILES['picture']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array($extension_upload, $extensions_autorisees)) {
                    $titleOk = preg_replace('~[\\\\/.,;:*!?"<>|]~', '', $title); // remplace les char par '' dans $title
                    $picture_path = $titleOk . "." . $extension_upload;
                    move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                }
            }
        } return $picture_path;
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
        if ($user->is_admin()) {
            $usertoAddRent = $user;
            $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
            $books = Book::get_all_books();
            $members = User::get_all_user();
            if (isset($_POST["delbook"])) {
                $delbook = Book::get_book_by_id($_POST["delbook"]);
            }
            if (isset($_POST["conf"])) {
                $delbook = Book::get_book_by_id($_POST["conf"]);
                $delbook->delete_book();
                $this->redirect("book", "index");
            }
            (new View("delete_confirm"))->show(array("book" => $delbook));
        } else
            $this->redirect();
//        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function book_detail() {
        $user = Controller::get_user_or_redirect();
        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
            $book = Book::get_book_by_id($value);
        } (new View("book_detail"))->show(array("book" => $book, "profile" => $user));
    }

    public function edit_book() {
        $user = $this->get_user_or_redirect();
        $book = "";
        $errors = [];
        $picture_path = "";
        if (isset($_POST['editbook'])) {
            $book = Book::get_book_by_id($_POST['editbook']);
        }
        if (isset($_POST["cancel"])) // bouton annuler
            $this->redirect("book", "index");

        if (isset($_POST["delimageH"])) {
            $edit = $_POST['delimageH'];
            $book = Book::get_book_by_id($edit);
            (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user));
            if (isset($_POST["idbook"])) { //pour delete QUE si on valide
                $this_book = Book::get_book_by_id($edit);
                $this_book->delete_image();
            }
        }
        if (isset($_POST['idbook']) && isset($_POST['isbn']) && isset($_POST['title']) && isset($_POST['editor']) && isset($_POST['author'])) {
            $book = Book::get_book_by_id($_POST["idbook"]);
            $this->validate_book($book, 'isbn', 'title', 'author', 'editor');
            $errors = $this->rules_add_book($book->isbn, $book->title, $book->author);

//            $picture_path = $this->add_picture($book->title, $picture_path);

            if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                if ($_FILES['picture']['error'] == 0) {
                    $infosfichier = pathinfo($_FILES['picture']['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension_upload, $extensions_autorisees)) {
                        $titleOk = preg_replace('~[\\\\/.,;:*!?"<>|]~', '', $book->title); // remplace les char par '' dans $book->title
                        $picture_path = $titleOk . "." . $extension_upload;
                        move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                    }
                }
            }
            if (empty($errors)) {
                $book->update_book();
                $this->redirect("book", "index");
            }
        }
        if (!isset($_POST["delimageH"])) {
            (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user, "picturepath" => $picture_path));
        }
    }

    private function validate_book($book, $isbn, $title, $author, $editor) {
        if (isset($_POST[$isbn]) && isset($_POST[$isbn]) !== "")
            $book->isbn = $this->isbn_format_string($_POST[$isbn]);
        if (isset($_POST[$title]) && isset($_POST[$title]) !== "")
            $book->title = $_POST[$title];
        if (isset($_POST[$author]) && isset($_POST[$author]) !== "")
            $book->author = $_POST[$author];
        if (isset($_POST[$editor]) && isset($_POST[$editor]) !== "")
            $book->editor = $_POST[$editor];
    }

    public static function isbn_format_EAN_13($isbn) {
        return substr($isbn, 0, 3) . "-" . substr($isbn, 3, 1) . "-"
                . substr($isbn, 4, 4) . "-" . substr($isbn, 8, 4) . "-" . substr($isbn, 12, 1);
    }

    public static function isbn_format_string($isbn) {
        return str_replace('-', '', $isbn);
    }

}
