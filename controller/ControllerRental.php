<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'ControllerBook.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerRental extends Controller {

    public function index() {
        Controller::redirect('Book', 'index');
    }

    

    public function search_book() {
        $profile = Controller::get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdate($profile->id);
        $title = "";
        $author = "";
        $date = "";
        $filter = "";
        if (isset($_POST["title"]) && isset($_POST["author"]) && isset($_POST["title"]) && isset($_POST["date"]) && isset($_POST["filtre"])) {
            $title = Tools::sanitize($_POST["title"]);
            $author = Tools::sanitize($_POST["author"]);
            $date = Tools::sanitize($_POST["date"]);
            $filter = Tools::sanitize($_POST["filtre"]);
            $books = Rental::get_rental_by_critere($title, $author, $filter);
        }
        (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
    }

    public function add_rental_in_basket() {// on recupere un user mais le champ id est vide
        $user = Controller::get_user_or_redirect();
        $users = User::get_user_by_username($user->username);
        $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
        $id = 0;
        $datetime = date("Y-m-d H:i:s");
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
        if (isset($_POST["idbook"])) {
            $rent = Book::get_book_by_id($_POST["idbook"]);
            if (Rental::rent_valid($users->id)) {
                $msg = "Vous ne pouvez pas louer plus de 5 livres a la fois";
            } else {

                $rental = new Rental($id, $users->id, $rent->id, NULL, NULL);
                $rental->insert_book_without_rent();
            }
             $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
            $getUserRental = $users->get_rental_join_book_join_user_by_user();
        }
        (new View("book_manager"))->show(array("books" => $books, "profile" => $users, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }

    public function add_rental_for_user_in_basket() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        $msg = " ";
        $usertoAddRent = "";
        $members = User::get_all_user();
        $datetime = date("Y-m-d H:i:s");
        $allrentofUser = Rental::get_this_rental_not_validate($user->id);
        if (isset($_POST["member_rent"])) {
            $value = $_POST["member_rent"];
            $usertoAddRent = User::get_user_by_username($value);
            if ($user->id != $usertoAddRent->id) {
                if (!Rental::rent_rented($usertoAddRent->id)) {
                    foreach ($allrentofUser as $rent) {
                        $rent->update_rental_rentdate_for_user($usertoAddRent->id, $datetime);
                    }
                } else {
                    $msg = "cet utilisateur a deja 5 location en cours";
                }
            } else {
                if (!Rental::rent_rented($user->id)) {
                    foreach ($allrentofUser as $rent) {
                        $rent->update_rental_rentdate($datetime);
                    }
                } else {
                    $msg = "vous avez deja 5 livre en location";
                }
            }
        }
        if (isset($_POST["annuler"])) {
            foreach ($allrentofUser as $rent) {
                $rent->delete_rental();
            }
        }
        $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }

    public function del_one_rental_in_basket() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
        if (isset($_POST["delrent"])) {
            $delrent = Rental::get_rental_by_id_book($_POST["delrent"]);
            foreach ($delrent as $del) {
                $del->delete_rental();
            }
            $getUserRental = $user->get_rental_join_book_join_user_by_user();
        }
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members));
    }
    
    public function returns() {
        $profile = Controller::get_user_or_redirect();
        $books = Rental::get_rentals_b($profile->id);
        $title = "";
        $author = "";
        $date = "";
        $filter = "";

        (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
    }

    public function delete_rental() {
        $profile = Controller::get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
        $title = "";
        $author = "";
        $date = "";
        $filter = "";
        if (isset($_POST["delrent"])) {
            var_dump($_POST["delrent"]);
            $delrent = Rental::get_rentals_by_id($_POST["delrent"]);
            foreach ($delrent as $del) {
                $del->delete_rental();
            }
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
        }
        (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
    }

    public function update_rental_returndate() {
        $profile = Controller::get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
        $title = "";
        $author = "";
        $date = "";
        $filter = "";
        $datetime = date("Y-m-d H:i:s");
        if (isset($_POST["idbook"])) {
            $returnRental = Rental::get_rentals_by_id($_POST["idbook"]);
            foreach ($returnRental as $return) {
                $return->update_rental_returndate($datetime);
            }
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
        }

        (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
    }

}
