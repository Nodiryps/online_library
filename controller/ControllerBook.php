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
        $msg = " ";

        if (isset($_POST["search"])) {
            $value = $_POST["search"];
            $books = Book::get_book_by_critere($value);
            $msg = " ";
        }
        if (empty($_POST["search"]))
            $books = Book::get_all_books();

        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

// on créé un livre sans img => comme ds msn
    public function add_book() {
        $user = $this->get_user_or_redirect();
        if ($user->is_admin()) {
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
                $errors = $this->rules_add_book($isbn, $title, $author, $editor);
                if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                    if ($_FILES['picture']['error'] == 0) {
                        $infosfichier = pathinfo($_FILES['picture']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                        if (in_array($extension_upload, $extensions_autorisees)) {
//                            $titleOk = preg_replace('~[\\\\/.,;:*!?"<>|]~', '', $title); // remplace les char par '' dans $title
                            $picture_path = $title . "." . $extension_upload;
                            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                        }
                    }
                } else
                    $picture_path = 'uploads/images.png';

                if (empty($errors)) {
                    $book = new Book(0, $isbn, $title, $author, $editor, $picture_path);
                    $book->create();
                    if (isset($_POST["idbook"]))
                        $this->redirect("book", "index");
                }
            } (new View("add_book"))->show(array("errors" => $errors, "isbn"));
        } else
            $this->redirect("book", "index");
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

    private function rules_add_book($isbn, $title, $author, $editor) {
        $errors = [];
        if(empty(trim($isbn)) || empty(trim($title)) || empty(trim($author)) || empty(trim($editor)))
            $errors[] = "TOUS les champs sont obligatoires !";
        if (strlen($isbn) !== 13)
            $errors[] = "isbn: isbn incorrect (13 chiffres)!";
        if (strlen($title) < 2)
            $errors[] = "titre: trop court (2 min.)!";
        if (strlen($author) < 5)
            $errors[] = "auteur.e: trop court (5 min.)!";
        if (strlen($editor) < 2)
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
                unlink("uploads/" . $delbook->picture);
                $this->redirect("book", "index");
            }
            (new View("delete_confirm"))->show(array("book" => $delbook));
        } else
            $this->redirect();
//        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function book_detail() {
        $user = $this->get_user_or_redirect();
        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
            $book = Book::get_book_by_id($value);
        } (new View("book_detail"))->show(array("book" => $book, "profile" => $user));
    }

    public function edit_book() {
        $user = $this->get_user_or_redirect();
        if ($user->is_admin()) {
            $book = "";
            $errors = [];
            $pathToDel = "";
            $bookpicToDel = "";
            if (isset($_POST['editbook'])) {
                $book = Book::get_book_by_id($_POST['editbook']);
            }
            if (isset($_POST["delimageH"])) { // boutton effacer img
                $edit = $_POST["delimageH"];
                $bookpicToDel = Book::get_book_by_id($edit);
                if($bookpicToDel->picture !== NULL) {
                    unlink("uploads/" . $bookpicToDel->picture);
                    $bookpicToDel->delete_image();
                } else
                    $errors[] = "Pas d'image à effacer...";
                $book = Book::get_book_by_id($edit);
                (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user)); // pour "refresh" l'img suppr
            }

            if (isset($_POST["cancel"])) { // boutton annuler
                $book = Book::get_book_by_id($_POST['cancel']);
                if (empty($errors)) {
                    $book->update();
                    $this->redirect("book", "index");
//                    $this->update_book($book);
                }
            }

            if (isset($_POST['idbook']) && isset($_POST['isbn']) && isset($_POST['title']) && isset($_POST['editor']) && isset($_POST['author'])) {
                $book = Book::get_book_by_id($_POST['idbook']);
//                $this->validate_book($book, $_POST['isbn'], $_POST['title'], $_POST['author'], $_POST['editor']);
                if (isset($_POST['isbn']) && isset($_POST['isbn']) !== "")
                    $book->isbn = $this->isbn_format_string($_POST['isbn']);
                if (isset($_POST['title']) && isset($_POST['title']) !== "")
                    $book->title = $_POST['title'];
                if (isset($_POST['author']) && isset($_POST['author']) !== "")
                    $book->author = $_POST['author'];
                if (isset($_POST['editor']) && isset($_POST['editor']) !== "")
                    $book->editor = $_POST['editor'];
                $errors = $this->rules_add_book($book->isbn, $book->title, $book->author, $book->editor);
                $picture_path = "";
                if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                    if ($_FILES['picture']['error'] == 0) {
                        $infosfichier = pathinfo($_FILES['picture']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                        if (in_array($extension_upload, $extensions_autorisees)) {
//                            $titleOk = preg_replace('~[\\\\/.,;:*!?"<>|]~', '', $book->title); // remplace les char par '' dans $book->title
                            $picture_path = $book->title . "." . $extension_upload;
                            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                        }
                    }
                } 
//                else
//                    $picture_path = 'uploads/images.png';
                
                if (empty($errors)) {
                    $book->update();
//                    $book = Book::get_author_by_id($book->id);
                    $this->redirect("book", "index");
//                    $this->update_book($book);
                }
            }
            if (!isset($_POST["delimageH"]))  // sinon 2 views qd on "refresh" avec le boutton effacer img
                (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user));
        } else
            $this->redirect("book", "index");
    }

    private function update_book($book) {
        $book->update();
        $this->redirect("book", "index");
    }

    private function delete_img($id) {
        $book = Book::get_book_by_id($id);
        $bookpicToDel = $book;
        $bookpicToDel->delete_image();
        (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user)); // pour "refresh" l'img suppr
    }

    private function validate_book($book, $isbn, $title, $author, $editor) {
        if (isset($isbn) && isset($isbn) !== "")
            $book->isbn = $this->isbn_format_string($isbn);
        if (isset($title) && isset($title) !== "")
            $book->title = $title;
        if (isset($author) && isset($author) !== "")
            $book->author = $author;
        if (isset($editor) && isset($editor) !== "")
            $book->editor = $editor;
    }

    public static function isbn_format_EAN_13($isbn) {
        return substr($isbn, 0, 3) . "-" . substr($isbn, 3, 1) . "-"
                . substr($isbn, 4, 4) . "-" . substr($isbn, 8, 4) . "-" . substr($isbn, 12, 1);
    }

    public static function isbn_format_string($isbn) {
        return str_replace('-', '', $isbn);
    }

}
