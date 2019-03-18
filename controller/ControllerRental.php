<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'ControllerBook.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'framework/Configuration.php';

class ControllerRental extends Controller {

    public function index() {
        $this->redirect('Book', 'index');
    }

    public function search_book() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin() || $profile->is_manager()) {
            $books = Rental::get_rental_join_book_join_user_rentdate($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";
            if (isset($_POST["title"]) && isset($_POST["author"]) && isset($_POST["title"]) && isset($_POST["date"]) && isset($_POST["filtre"])) {
                $title = $_POST["title"];
                $author = $_POST["author"];
                $date = $_POST["date"];
                $filter = $_POST["filtre"];
                $books = Rental::get_rental_by_critere($title, $author, $filter, $date);
            }
            (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }

    public function add_rental_in_basket() {// on recupere un user mais le champ id est vide
        $user = $this->get_user_or_redirect();
        $users = User::get_user_by_username($user->username);
        $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
        $id = 0;
        $datetime = date("Y-m-d H:i:s");
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
        $usertoAddRent = "";

        if (isset($_POST["idbook"]) && isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            $rent = Book::get_book_by_id($_POST["idbook"]);
            if (!Rental::cpt_basket_ok($usertoAddRent->id) ) {
                $msg = "Vous ne pouvez pas louer plus de 5 livres à la fois!";
            } else {
                if ($user->id !== $usertoAddRent->id && intval($rent->nbCopies) > $rent->nbCopies_of_a_book()) {
                    $rental = new Rental($id, $usertoAddRent->id, $rent->id, NULL, NULL);
                    $rental->insert_book_without_rent();
                } else {
                    $rental = new Rental($id, $users->id, $rent->id, NULL, NULL);
                    $rental->insert_book_without_rent();
                }
            }

            $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
        }
        (new View("book_manager"))->show(array("books" => $books, "profile" => $users, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function rent_books_in_basket() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books($user->id);
        $msg = " ";
        $usertoAddRent = "";
        $members = User::get_all_user();
        $datetime = date("Y-m-d H:i:s");

        if (isset($_POST["panierof"])) {
            $value = $_POST["panierof"];
            $usertoAddRent = User::get_user_by_id($value);
            $allrentofUser = Rental::get_this_rental_not_validate($usertoAddRent->id);
            if ($user->id != $usertoAddRent->id) {
                if (Rental::cpt_book_rented_ok($usertoAddRent->id)) {
                    foreach ($allrentofUser as $rent) {
                        $rent->update_rental_rentdate_for_user($usertoAddRent->id, $datetime);
                    }
                } else {
                    $msg = "Cet utilisateur a déjà 5 locations en cours!";
                }
            } else {
                if (Rental::cpt_book_rented_ok($usertoAddRent->id)) {
                    foreach ($allrentofUser as $rent) {
                        $rent->update_rental_rentdate($datetime);
                    }
                } else {
                    $msg = "Vous avez déjà 5 livres en location!";
                }
            }
        }
        if (isset($_POST["annuler"]) && isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            foreach ($allrentofUser as $rent) {
                $rent->delete_rental();
            }
        }
        if (isset($_POST["solo"])) {
            if (Rental::cpt_book_rented_ok($user->id)) {
                foreach ($allrentofUser as $rent) {
                    $rent->update_rental_rentdate($datetime);
                }
            } else {
                $msg = "Vous avez déjà 5 livres en location!";
            }
        }
        $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user_not_rented();
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function get_basket() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books($user->id);
        $msg = " ";
        $usertoAddRent = "";
        $members = User::get_all_user();
        $datetime = date("Y-m-d H:i:s");
        $allrentofUser = Rental::get_this_rental_not_validate($user->id);
        if (isset($_POST["panierof"])) {
            $value = $_POST["member_rents"];
            $usertoAddRent = User::get_user_by_id($value);
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user_not_rented();
        }


        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function del_one_rental_in_basket() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books($user->id);
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
        $usertoAddRent = "";
        if (isset($_POST["delrent"]) && isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            $delrent = Rental::get_rental_by_id_book($_POST["delrent"]);
            foreach ($delrent as $del) {
                $del->delete_rental();
            }
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
        }
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function returns() {
        $profile = $this->get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
        $title = "";
        $author = "";
        $date = "";
        $filter = "";

        (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
    }

    public function delete_rental() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin()) {
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";
            if (isset($_POST["delrent"])) {
                $delrent = Rental::get_rentals_by_id($_POST["delrent"]);
                foreach ($delrent as $del) {
                    $del->delete_rental();
                }
                $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            }
            (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }

    public function update_rental_returndate() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin() || $profile->is_manager()) {
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
        } else
            $this->redirect();
    }

    public function cancel_rental_returndate() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin() || $profile->is_manager()) {
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";

            if (isset($_POST["idcancel"])) {
                $returnRental = Rental::get_rentals_by_id($_POST["idcancel"]);
                foreach ($returnRental as $return) {
                    $return->cancel_rental_returndate();
                }
                $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            }

            (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }
    
   

}
