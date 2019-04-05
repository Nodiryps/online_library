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
        $books = Book::get_all_books($user->id);
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
            $books = Book::get_all_books($user->id);

        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function add_book() {
        $user = $this->get_user_or_redirect();
        if ($user->is_admin()) {
            $isbn = "";
            $title = "";
            $author = "";
            $editor = "";
            $errors = [];
            $picture_path = "";
            $nbcopies = "";

            if (isset($_POST["isbn"]) && isset($_POST["author"]) && isset($_POST["title"]) && isset($_POST["editor"]) && isset($_POST["nbCopie"])) {
                $isbn = $_POST["isbn"];
                $title = $_POST["title"];
                $author = $_POST["author"];
                $editor = $_POST["editor"];
                $nbcopies = $_POST["nbCopie"];
                $errors = Book::rules_add_book($isbn, $title, $author, $editor, $nbcopies);
                if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                    if ($_FILES['picture']['error'] == 0) {
                        $infosfichier = pathinfo($_FILES['picture']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'PNG', 'png', 'JPG', 'JPEG', 'GIF');
                        if (in_array($extension_upload, $extensions_autorisees)) {
                            $titleOk = Book::titleOk($title); // remplace les char par '' dans $title
                            $picture_path = $titleOk . "." . $extension_upload;
                            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                        }
                    }
                } else {
                    $picture_path = NULL;
                }

                if (Book::existIsbn($isbn))
                    $errors[] = "ISBN existe deja !";
                if (empty($errors)) {
                    $book = new Book(0, self::calcul_isbn($isbn), $title, $author, $editor, $picture_path, $nbcopies);
                    $book->create();
                    $this->redirect("book", "index");
                }
            }
        }
        (new View("add_book"))->show(array("errors" => $errors, "isbn" => $isbn, "title" => $title, "author" => $author, "editor" => $editor, "nbCopie" => $nbcopies));
    }

    public function delete_book() {
        $user = Controller::get_user_or_redirect();
        if ($user->is_admin()) {
            $usertoAddRent = $user;
            $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
            $books = Book::get_all_books($usertoAddRent->id);
            $members = User::get_all_user();
            if (isset($_POST["delbook"])) {
                $delbook = Book::get_book_by_id($_POST["delbook"]);
            }
            if (isset($_POST["conf"])) {
                $delbook = Book::get_book_by_id($_POST["conf"]);
                unlink("uploads/" . $delbook->picture);
                $delbook->delete_book();
                $this->redirect("book", "index");
            }

            (new View("delete_confirm"))->show(array("book" => $delbook));
        }
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
            $oldpath = "";
            $bookpicToDel = "";
            $nbCopie = "";
            $picture_path = "";
            if (isset($_POST['editbook'])) {
                $book = Book::get_book_by_id($_POST['editbook']);
                $oldpath = $book->picture;
                $book->isbn = substr($book->isbn, 0, 12);
            }
            if (isset($_POST["cancel"]))  // boutton annuler
                $this->redirect("book", "index");
            if (isset($_POST['idbook']) && isset($_POST['isbn']) || isset($_POST['title']) || isset($_POST['editor']) || isset($_POST['author']) || isset($_POST['nbCopie'])) {
                $book = Book::get_book_by_id($_POST['idbook']);
                $book->isbn = substr($book->isbn, 0, 12);
                self::set_book_attr($book);
                if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                    if ($_FILES['picture']['error'] == 0) {
                        $infosfichier = pathinfo($_FILES['picture']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'PNG', 'png', 'JPG', 'JPEG', 'GIF');
                        if (in_array($extension_upload, $extensions_autorisees)) {

                            $titleOk = Book::titleOk($book->title); // remplace les char par '' dans $title
                            $picture_path = $titleOk . "." . $extension_upload;
                            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                            $book->picture = $picture_path;
                        }
                    }
                }
                $errors = Book::rules_add_book($book->isbn, $book->title, $book->author, $book->editor, $book->nbCopies);
                if (empty($errors)) {
                    $book->isbn = self::calcul_isbn($book->isbn);
                    $book->update();
                    $this->redirect("book", "index");
                }
            }
            (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user, "nbCopie" => $nbCopie));
        } else
            $this->redirect("book", "index");
    }

    public function delete_img() {
        $user = $this->get_user_or_redirect();
        if ($user->is_admin()) {
            $book = "";
            $errors = [];
            $nbCopie = "";
            if (isset($_POST["delimageH"])) {
                $edit = $_POST["delimageH"];
                $book = Book::get_book_by_id($edit);
                if ($book->picture !== NULL) {
                    $book->delete_image();
                    unlink("uploads/" . $book->picture);
                }
                $book = Book::get_book_by_id($edit);
                (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user, "nbCopie" => $nbCopie)); // pour "refresh" l'img suppr
            }
        } else
            $this->redirect("book", "index");
    }

    private static function set_book_attr(&$book) {
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

    public static function isbn_format_EAN_13($isbn) {
        return substr($isbn, 0, 3) . "-" . substr($isbn, 3, 1) . "-"
                . substr($isbn, 4, 4) . "-" . substr($isbn, 8, 4) . "-" . substr($isbn, 12, 1);
    }

    public static function calcul_isbn($isbn) {
        $total = 0;
        $rest = 0;
        $isbn = str_replace(' ', '', $isbn);
        $tabIsbn = str_split($isbn, 1);
        for ($i = 1; $i <= sizeof($tabIsbn); ++$i) {
            if ($i % 2 == 0) {
                $tabIsbn[$i - 1] *= 3;
            }
            if ($i % 2 != 0) {
                $tabIsbn[$i - 1] *= 1;
            }
        }
        $total = self::addition_isbn($tabIsbn);
        if ($total % 10 != 0) {
            $rest = (int) 10 - (int) ($total % 10);
        }
        return $isbn . $rest;
    }

    public static function isbn_format_string($isbn) {
        return str_replace('-', '', $isbn);
    }

    public static function addition_isbn($isbn) {
        $res = 0;
        foreach ($isbn as $s) {
            $res += $s;
        }
        return $res;
    }

    public function functionName($param) {
        
    }

}
