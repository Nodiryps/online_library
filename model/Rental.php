<?php

require_once 'framework/Model.php';
require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'framework/Configuration.php';

class Rental extends Model {

    var $id;
    var $user;
    var $book;
    var $rentaldate;
    var $returndate;

    public function __construct($id, $user, $book, $rentaldate, $returndate) {
        $this->id = $id;
        $this->user = $user;
        $this->book = $book;
        $this->rentaldate = $rentaldate;
        $this->returndate = $returndate;
    }

    public function insert_book_without_rent() {
        try {
            self::execute("INSERT INTO rental(id,user, book, rentaldate,returndate) "
                    . "VALUES(:id,:user, :book, :rentaldate, :returndate)", array("id" => $this->id, "user" => $this->user, "book" => $this->book,
                "rentaldate" => $this->rentaldate, "returndate" => $this->returndate));
            return $this;
        } catch (Exception $ex) {
            
        }
    }

    public static function get_all_rental() {
        $results = [];
        try {
            $query = self::execute("SELECT * FROM rental", array());
            $books = $query->fetchAll();
            foreach ($books as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    // faire une requete de jointure 
    public static function get_this_rental_not_validate($id) {
        $results = [];
        try {
            $query = self::execute("SELECT * FROM rental WHERE user=:id AND rentaldate IS NULL", array("id" => $id));
            $books = $query->fetchAll();
            foreach ($books as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_join_book_join_user() {
        $results = [];
        try {
            $query = self::execute("SELECT DISTINCT* FROM (rental join user on rental.user=user.id) join book on rental.book=book.id", array());
            $rental = $query->fetchAll();
            foreach ($rental as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_join_book_join_user_rentdate($id) {
        $results = [];
        try {
            $query = self::execute("SELECT * FROM book WHERE book.id NOT IN (select book.id from rental JOIN book on rental.book=book.id where rentaldate is null and rental.user= :id)", array("id" => $id));
            $rental = $query->fetchAll();
            foreach ($rental as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"], $row["nbCopies"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_join_book_join_user_rentdates() {
        $results = [];
        try {
            $query = self::execute("SELECT DISTINCT* FROM rental WHERE rentaldate IS NOT NULL", array());
            $rental = $query->fetchAll();
            foreach ($rental as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_join_book_join_user_returndate() {
        $results = [];
        try {
            $query = self::execute("SELECT DISTINCT* FROM (rental join user on rental.user=user.id) join book on rental.book=book.id WHERE returndate IS NOT NULL", array());
            $rental = $query->fetchAll();
            foreach ($rental as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_join_book_join_user_all() {
        $results = [];
        try {
            $query = self::execute("SELECT * FROM (rental join user on rental.user=user.id) join book on rental.book=book.id WHERE rentaldate IS NOT NULL  OR returndate IS NOT NULL", array());
            $rental = $query->fetchAll();
            foreach ($rental as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    // methode un peu tendnu a faire.
    public static function get_rental_by_critere($title, $author, $filter, $date) {
        $results = [];
        try {
            if ($filter == "tous") {
                $books = self::execute("SELECT * FROM (rental join user on rental.user=user.id) join book on rental.book=book.id WHERE( rentaldate IS NOT NULL  OR returndate IS NOT NULL) AND book.title LIKE :title AND book.author LIKE :author AND rentaldate LIKE :date", array(":title" => "%" . $title . "%", ":author" => "%" . $author . "%", ":date" => "%" . $date . "%"));
                $query = $books->fetchAll();
                foreach ($query as $row) {
                    $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
                }
                return $results;
            }
            if ($filter == "retour") {
                $query = self::execute("SELECT DISTINCT* FROM (rental join user on rental.user=user.id) join book on rental.book=book.id WHERE returndate IS NOT NULL AND book.title LIKE :title AND book.author LIKE :author", array(":title" => "%" . $title . "%", ":author" => "%" . $author . "%"));
                $rental = $query->fetchAll();
                foreach ($rental as $row) {
                    $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
                }
                return $results;
            }
            if ($filter == "location") {
                $books = self::execute("SELECT * FROM (rental join user on rental.user=user.id) join book on rental.book=book.id "
                                . "WHERE (rentaldate IS NOT NULL  AND returndate IS NULL) AND book.title LIKE :title AND book.author LIKE :author", array(":title" => "%" . $title . "%", ":author" => "%" . $author . "%"));
                $query = $books->fetchAll();
                foreach ($query as $row) {
                    $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
                }
                return $results;
            }
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function cpt_basket_ok($id) {
        try {
            $query = self::execute("SELECT * FROM rental WHERE user=:id and rentaldate is null", array("id" => $id));
            $books = $query->fetchAll();

            return sizeof($books) < Configuration::get("max_rents");
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function cpt_book_rented_ok($id) {
        try {
            $query = self::execute("SELECT * FROM rental WHERE user=:id and rentaldate is not null", array("id" => $id));
            $books = $query->fetchAll();

            return sizeof($books) < Configuration::get("max_rents");
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_by_id_book($id) {
        $results = [];
        try {
            $query = self::execute("SELECT * FROM rental WHERE book=:id", array("id" => $id));
            $books = $query->fetchAll();
            foreach ($books as $row) {
                $results[] = new Rental($row["id"], $row["user"], $row["book"], $row["rentaldate"], $row["returndate"]);
            }
            return $results;
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rentals_by_user($user) {
        $res = [];
        try {
            $query = self::execute("SELECT * FROM rental WHERE user = :user AND rentaldate IS NOT NULL AND returndate is NULL", array("user" => $user));
            $rentals = $query->fetchAll();
            foreach ($rentals as $rental)
                $res[] = new Rental($rental["id"], $rental["user"], $rental["book"], $rental["rentaldate"], $rental["returndate"]);
            return $res;
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès à la base de données.");
        }
    }

    public static function get_rentals_b($user) {
        $res = [];
        try {
            $query = self::execute("SELECT DISTINCT* FROM (rental join user on rental.user=user.id) join book on rental.book=book.id WHERE rentaldate IS NOT NULL", array("user" => $user));
            $rentals = $query->fetchAll();
            foreach ($rentals as $rental) {
                $res[] = new Rental($rental["id"], $rental["user"], $rental["book"], $rental["rentaldate"], $rental["returndate"]);
            }
            return $res;
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès à la base de données.");
        }
    }

    public static function get_rentals_by_id($id) {
        $res = [];
        try {
            $query = self::execute("SELECT * FROM rental WHERE id = :id ", array("id" => $id));
            $rentals = $query->fetchAll();
            foreach ($rentals as $rental) {
                $res[] = new Rental($rental["id"], $rental["user"], $rental["book"], $rental["rentaldate"], $rental["returndate"]);
            }
            return $res;
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès à la base de données.");
        }
    }

    // methode pas a la bonne place->dans BOOK
    public function get_book() {
        try {
            $query = self::execute("SELECT * FROM book WHERE id = :id", array("id" => $this->book));
            $book = $query->fetch();
            return new Book($book["id"], $book["isbn"], $book["title"], $book["author"], $book["editor"], $book["picture"], $book["nbCopies"]);
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_rental_count() {
        try {
            $query = self::execute("SELECT COUNT(*) FROM rental WHERE rentaldate =null ", array());
            return $query->fetch();
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function delete_rental() {
        try {
            self::execute("DELETE FROM rental WHERE  id=:id", array("id" => $this->id));
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function is_already_rented($book, $user) {
        try {
            $query = self::execute("SELECT * FROM rental WHERE book=:book AND user=:user", array("book" => $book, "user" => $user));
            $books = $query->fetchAll();
            return count($books) != 0;
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function update_rental_rentdate($rentaldate) {
        self::execute("UPDATE rental SET rentaldate = :rentaldate WHERE id=:id  ", array("rentaldate" => $rentaldate, "id" => $this->id));
    }

    public function update_rental_returndate($returndate) {
        self::execute("UPDATE rental SET returndate = :returndate WHERE id=:id  ", array("returndate" => $returndate, "id" => $this->id));
    }

    public function cancel_rental_returndate() {
        self::execute("UPDATE rental SET returndate = :returndate WHERE id=:id  ", array("returndate" => null, "id" => $this->id));
    }

    public function update_rental_rentdate_for_user($user, $rentaldate) {
        self::execute("UPDATE rental SET user=:user, rentaldate = :rentaldate WHERE id=:id ", array("user" => $user, "rentaldate" => $rentaldate, "id" => $this->id));
    }

    public static function get_nbBook_rental($id) {
        try {
            $query = self::execute("SELECT * FROM rental WHERE book=:id where returndate IS NULL", array("id" => $id));
            $book = $query->fetchAll();
            if (count($book) == 0) {
                $books = Book::get_book_by_id($id);
                return $books;
            } else {
                return count($book);
            }
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public static function get_nbBook_rental_bool($id) {
        try {
            $query = self::execute("SELECT * FROM rental WHERE book=:id where returndate IS NULL", array("id" => $id));
            $book = $query->fetchAll();
            return count($book) > 0;
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public static function rent_own_basket($usertoAddRent, $allrentofUser) {
        $msg = "";
        if (Rental::cpt_book_rented_ok($usertoAddRent->id))
            foreach ($allrentofUser as $rent)
                $rent->update_rental_rentdate(date("Y-m-d H:i:s"));
        else
            $msg = "Vous avez déjà 5 livres en location!";
        return $msg;
    }

    private static function rent_for_someone_else_basket($usertoAddRent, $allrentofUser) {
        $msg = "";
        if (Rental::cpt_book_rented_ok($usertoAddRent->id))
            foreach ($allrentofUser as $rent)
                $rent->update_rental_rentdate_for_user($usertoAddRent->id, date("Y-m-d H:i:s"));
        else
            $msg = "Cet utilisateur a déjà 5 locations en cours!";
        return $msg;
    }

    public static function rent_basket($user, $usertoAddRent, $allrentofUser, &$msg) {
        if ($user->id != $usertoAddRent->id)
            $msg = self::rent_for_someone_else_basket($usertoAddRent, $allrentofUser);
        else
            $msg = self::rent_own_basket($usertoAddRent, $allrentofUser);
    }

    public static function clear_basket($allrentofUser) {
        foreach ($allrentofUser as $rent)
            $rent->delete_rental();
    }

    public static function delete_rentals($rentalsTab) {
        foreach ($rentalsTab as $r)
            $r->delete_rental();
    }

    public static function is_nbCopies_ok($rent) {
        return intval($rent->nbCopies) > $rent->nbCopies_of_a_book();
    }

    public static function add_to_basket($user, $usertoAddRent, $rent, $id, $users) {
        if ($user->id !== $usertoAddRent->id && Rental::is_nbCopies_ok($rent)) {
            $rental = new Rental($id, $usertoAddRent->id, $rent->id, NULL, NULL);
            $rental->insert_book_without_rent();
        } else {
            $rental = new Rental($id, $users->id, $rent->id, NULL, NULL);
            $rental->insert_book_without_rent();
        }
    }

}
