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


        if (isset($_POST["search"])) {
            $value = $_POST["search"];
            $books = Book::get_book_by_critere($value);
        }
        if (empty($_POST["search"])) {
            $books = Book::get_all_books();
        }
        (new View("book_manager"))->show(array("books" => $books,"profile"=>$user));
    }

    public function books_manager() {
        $user = Controller::get_user_or_redirect();
        $books = Book::get_all_books();
        var_dump($books);
    }

    public function panier() {
        $user = Controller::get_user_or_redirect();
        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
        }
        (new View("book_detail"))->show(array("books" => $books));
    }

    public function book_detail() {
        $user = Controller::get_user_or_redirect();
        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
            $book = Book::get_book_by_id($value);
        }
        (new View("book_detail"))->show(array("book" => $book));
    }

    public function add_rental() {
        $user = Controller::get_user_or_redirect();
         $books = Book::get_all_books();
         $id="";
         $datetime=new DateTime();
         $test= Rental::get_all_rental();
         var_dump($test);
        if(isset($_POST["idbook"])){
            $value = $_POST["idbook"];
            $rent= Book::get_book_by_id($value);
            $rental=new Rental($id, $user->id,$rent->id , $datetime,NULL);
            $rental->insert_book_without_rent();
        }
         (new View("book_manager"))->show(array("books"=>$books,"rental"=>$rental));
    }

}
