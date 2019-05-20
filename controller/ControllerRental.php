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
              $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";

            if (isset($_POST["title"]) || isset($_POST["author"])  || isset($_POST["date"]) || isset($_POST["filtre"])) {
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

    public function add_rental_in_basket() {
        $user = $this->get_user_or_redirect();
        $users = User::get_user_by_username($user->username);
        $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
        $id = 0;
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
        $usertoAddRent = "";

        if (isset($_POST["idbook"]) && isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            $rent = Book::get_book_by_id($_POST["idbook"]);
            if (!Rental::cpt_basket_ok($usertoAddRent->id)) {
                $msg = "Vous ne pouvez pas louer plus de 5 livres Ã  la fois!";
            } else {
                Rental::add_to_basket($user, $usertoAddRent, $rent, $id, $users);
            }
            $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
        } (new View("book_manager"))->show(array("books" => $books, "profile" => $users, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function rent_books_in_basket() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books($user->id);
        $msg = " ";
        $usertoAddRent = "";
        $members = User::get_all_user();

        if (isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            $allrentofUser = Rental::get_this_rental_not_validate($usertoAddRent->id);
            Rental::rent_basket($user, $usertoAddRent, $allrentofUser, $msg);
        }
        if (isset($_POST["annuler"]) && isset($_POST["panierof"])) {
            Rental::clear_basket($allrentofUser);
            $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
            $allrentofUser = Rental::get_this_rental_not_validate($usertoAddRent->id);
            (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
        }
        if (isset($_POST["solo"])) {
            $msg = Rental::rent_own_basket($usertoAddRent, $allrentofUser);
            $allrentofUser = Rental::get_this_rental_not_validate($usertoAddRent->id);
        }
        $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user_not_rented();
        if (!isset($_POST["annuler"]))
            (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function get_basket() {
        $user = $this->get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
        $msg = " ";
        $usertoAddRent = "";
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $members = User::get_all_user();
        $filter = [];
        if (isset($_GET["param1"])) {
            $filter = Tools::url_safe_decode($_GET['param1']);

            if (!$filter)
                Tools::abort("bad url parameter");
        }
        if (isset($_POST["member_rents"])) {
            $filter["member_rents"] = $_POST["member_rents"];
            $this->redirect("Rental", "get_basket", Tools::url_safe_encode($filter));
        }
        if ($filter) {
            $value = $filter["member_rents"];
            $usertoAddRent = User::get_user_by_id($value);
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user_not_rented();
            $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);
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
            Rental::delete_rentals(Rental::get_rental_by_id_book($_POST["delrent"]));
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
        }
        $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function returns() {
        $profile = $this->get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
        $title = "";
        $author = "";
        $date = "";
        $filter = "";
        //var_dump(Rental::get_rental_join_book_join_user_rentdatesJs());
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
                Rental::delete_rentals(Rental::get_rentals_by_id($_POST["delrent"]));
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
                foreach ($returnRental as $return)
                    $return->update_rental_returndate($datetime);
                $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            } (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
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
            } (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }

    public function getReturns() {

//            $rentaldate = null;
//            $title = "";
//            $author = "";
//             $select="";
//
//            if (isset($_POST['username'])) {
//                $user = $_POST['username'];
//            }
//
//            if (isset($_POST['book'])) {
//                $book = $_POST['book'];
//            }
//
//            if (isset($_POST['rentaldate'])) {
//                $rentaldate = $_POST['rentaldate'];
//            }
////
//            if (isset($_POST['autres']) && $_POST['autres'] == "true") {
//                $select = "tous";
//            } else if (isset($_POST['autre1']) && $_POST['autre1'] == "true") {
//                $select = "location";
//            } else if (isset($_POST['autre2']) && $_POST['autre2'] == "true") {
//                $select = "retour";
//            }
//             
//               $rent[] = Rental::get_rental_by_critere($title,$author,$select,$rentaldate);

        $rent = Rental::get_rental_join_book_join_user_rentdatesJs();
        echo json_encode($rent);
    }

    public function getRentalsRessources() {
        $rentaldate = null;
        $title = "";
        $author = "";
        $select = "tous";
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
        }
        if (isset($_POST['author'])) {
            $author = $_POST['author'];
        }
        if (isset($_POST['rentaldate'])) {
            $rentaldate = $_POST['rentaldate'];
        }
        if (isset($_POST['select']) && $_POST['select'] === "") {
            $select = $_POST['select'];
        }
        if ($title == "" && $author == "" && $rentaldate == null) {
            $rentals = Rental::get_rental_join_book_join_user_rentdatesJs();
        } else {
            $rentals = Rental::get_rental_join_book_join_user_rentdatesFilterJs($title, $author, $rentaldate, $select);
        }
        if ($rentals != null) {
            foreach ($rentals as $rental) {
                if ($rental->returndate == NULL) {
                    if (date('Y-m-d', strtotime('1 month', strtotime($rental->rentaldate))) >= date('Y-m-d')) {
                        $rental->eventColor = 'green';
                    } else {
                        $rental->eventColor = 'red';
                    }
                } else {
                    if (date('Y-m-d', strtotime('1 month', strtotime($rental->rentaldate))) >= $rental->returndate) {
                        $rental->eventColor = 'lightgreen';
                    } else {
                        $rental->eventColor = 'lightred';
                    }
                }
                if ($rental->returndate == null) {
                    $rental->end = "pas encore retourner";
                } else {
                    $rental->end = $rental->returndate;
                }
            }
        }
        echo json_encode($rentals);
    }

    public function getRentalsEvents() {
        $rentals = Rental::get_rental_join_book_join_user_rentdatesJs();
        foreach ($rentals as $rental) {
            $rental->resourceId = $rental->id;
            $rental->start = $rental->rentaldate;
            if ($rental->returndate == NULL) {
                if (date('Y-m-d h:i:s', strtotime('1 month', strtotime($rental->rentaldate))) >= date('Y-m-d h:i:s')) {
                    $rental->end = date('Y-m-d h:i:s', strtotime('1 month', strtotime($rental->rentaldate)));
                } else {
                    $rental->end = date('Y-m-d h:i:s');
                }
            } else {
                $rental->end = $rental->returndate;
            }
        }

        echo json_encode($rentals);
    }

    public function returnDate() {
        if (isset($_POST['rentId'])) {
            echo $_POST['rentId'];
//            $rent = Rental::get_rentals_by_id($_POST['param1']);
//            $rent->update_rental_returndate(new DateTime());
        }
    }

}
