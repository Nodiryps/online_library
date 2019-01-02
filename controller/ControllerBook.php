<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'model/Rental.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerBook extends Controller {

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
                if (Tools::isset_notEmpty($_POST["isbn"]) && Tools::isset_notEmpty($_POST["author"]) 
                        && Tools::isset_notEmpty($_POST["title"]) && Tools::isset_notEmpty($_POST["editor"])) {
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
        if(strlen($title) < 2)
            $errors[] = "titre: trop court (2 min.)!";
        if(strlen($author) < 5)
            $errors[] = "auteur.e: trop court (5 min.)!";
        if(strlen($author) < 2)
            $errors[] = "édition: trop court (2 min.)!";
        return $errors;
    }

//    public function edit_book($book) {
//        $profile = '';
//        $picture_path = '';
//
//        if (isset($_POST["editbook"]) && $_POST["editbook"] !== "") {
//            if (isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
//                if ($_FILES['picture']['error'] == 0) {
//                    $typeOK = TRUE;
//                    $path = $this->is_path_ok($book);
//
//                    if ($path === "") {
//                        $typeOK = FALSE;
//                        $error = "Formats supportés: gif, jpeg ou png !";
//                    }
//                    if ($typeOK) {
//                        move_uploaded_file($_FILES['picture']['tmp_name'], $path);
//                        if ($book->update($book->isbn, $book->title, $book->author, $book->editor, $book->picture)) {
//                            $success = "Your profile has been successfully updated.";
//                        }
//                    }
//                } else {
//                    $error = "Une erreur est survenue lors du téléchargement de l'image.";
//                }
//                return $error;
//            }
//        }
//        if (isset($_POST['profile'])) {
//            $profile = sanitize($_POST['profile']);
//            if (update_member($user, $profile, NULL)) {
//                $success = "Your profile has been successfully updated.";
//            }
//        }
//
//        $member = get_member($user);
//        $profile = $member['profile'];
//        $picture_path = $member['picture_path'];
//    }

//    private function is_path_ok($book) {
//        $path = "";
//        if ($_FILES['picture']['type'] == "picture/gif")
//            $path = $book . ".gif";
//        else if ($_FILES['picture']['type'] == "picture/jpeg")
//            $path = $book . ".jpg";
//        else if ($_FILES['picture']['type'] == "picture/png")
//            $path = $book . ".png";
//        return $path;
//    }

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

    public function edit_book() {
        $user = Controller::get_user_or_redirect();
        $book = Book::get_book_by_id($_POST["editbook"]);
        $error = "";

        if (isset($_POST["editbook"])) {
            $book = Book::get_book_by_id($_POST["editbook"]);
        }
        if (isset($_POST["idbook"]) && $book !== "") {
            if (!$book->edit_book($book->id))
                $error = "Erreur lors de l'édition du bouquin '$book->title' (ISBN: $book->isbn).";
        }
        //repris de msn vue en classe a adapter au projet pour uploader une image
          if (isset($_FILES['image']) && $_FILES['image']['error'] === self::UPLOAD_ERR_OK) {
            $errors = Member::validate_photo($_FILES['image']);
            if (empty($errors)) {
                $saveTo = $member->generate_photo_name($_FILES['image']);
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
        (new View("edit_book"))->show(array("book" => $book, "error" => $error,"profile"=>$user));
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
}
