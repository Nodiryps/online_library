<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerRental extends Controller {

    public function index() {
        (new View("basket"))->show();
    }
    
    public function returns() {
        (new View("returns"))->show();
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
            $getUserRental = "";
        } else {

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
}
