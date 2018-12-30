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
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
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

    public function delete_book() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        $pannier = "";
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
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

    public function add_rental() {// on recupere un user mais le champ id est vide
        $user = Controller::get_user_or_redirect();
        $users = User::get_user_by_username($user->username);
        $books = Book::get_all_books();
        $id = 0;
        $datetime = date("Y-m-d H:i:s");
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
         
        $msg = " ";
        $members = User::get_all_user();

        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
            $rent = Book::get_book_by_id($value);
            if (!Rental::rent_valid($users->id)) {
                $msg = "Vous ne pouvez pas louer plus de 5 livres";
            } else {
                $rental = new Rental($id, $users->id, $rent->id, NULL, NULL);
                $rental->insert_book_without_rent();
            }
            $getUserRental = $users->get_rental_join_book_join_user_by_user();
        }
        (new View("book_manager"))->show(array("books" => $books, "profile" => $users, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }

    public function add_rental_for_user() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
         $datetime = date("Y-m-d H:i:s");
        if (isset($_POST["member_rent"])) {
            $value = $_POST["member_rent"];
            $usertoAddRent = User::get_user_by_username($value);
            var_dump($usertoAddRent);
//            if ($user->id != $usertoAddRent->id) {
//               $allrentofUser= Rental::get_this_rental($usertoAddRent->id);
//               foreach ($allrentofUser as $rent){
//                   $rent->update_rental_rentdate($datetime);
//               }
               $getUserRental="";
                   
               }else{
                   
//               }
            }
        
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }

    public function del_one_rent() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();

        if (isset($_POST["delrent"])) {
            $value = $_POST["delrent"];
            $delrent = Renget_rental_by_id_bookby_id($value);
            foreach ($delrent as $del) {
                $del->delete_rental();
            }
            $getUserRental = $user->get_rental_join_book_join_user_by_user();
        }
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }

    public function edit_book() {
        $book = Book::get_book_by_id($_POST["editbook"]);
        $error = "";

        if (isset($_POST["editbook"])) {
            $book = Book::get_book_by_id($_POST["editbook"]);
        }
        if (isset($_POST["idbook"]) && $book !== "") {
            if (!$book->edit_book($book->id))
                $error = "Erreur lors de l'édition du bouquin '$book->title' (ISBN: $book->isbn).";
        }
        (new View("edit_book"))->show(array("book" => $book, "error" => $error));
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
