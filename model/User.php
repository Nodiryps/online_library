<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Spy
 */
require_once 'framework/Model.php';
require_once 'model/Book.php';
require_once 'model/Rental.php';
require_once 'framework/Tools.php';

class User extends Model {

    var $id;
    var $username;
    var $hash_password;
    var $fullname;
    var $email;
    var $birthdate;
    var $role;

    public function __construct($id, $username, $password, $fullname, $email, $birthdate, $role) {
        $this->id = $id;
        $this->username = $username;
        $this->hash_password = $password;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->role = $role;
    }

    public function validate() {
        $errors = array();
        if (!(isset($this->username) && is_string($this->username) && strlen($this->username) > 0)) {
            $errors[] = "Pseudo is required.";
        } if (!(isset($this->username) && is_string($this->username) && strlen($this->username) >= 3 && strlen($this->username) <= 16)) {
            $errors[] = "Pseudo length must be between 3 and 16.";
        } if (!(isset($this->username) && is_string($this->username) && preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->username))) {
            $errors[] = "Pseudo must start by a letter and must contain only letters and numbers.";
        } if (!User::is_email_available($this->email)) {
            $errors[] = "l'email existe deja";
        }
        if($this->hash_password=="" || empty($this->hash_password)){
            $errors[]="le mot de passe est obligatoire et le confirme de mot de passe est obligatoire";
        }
        return $errors;
    }

    public static function validate_unicity($pseudo) {
        $errors = [];
        $member = self::is_username_not_available($pseudo);
        if (!$member) {
            $errors[] = "Pseudo existant!";
        }
        return $errors;
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_login($pseudo, $password) {
        $errors = [];
        $member = self::get_user_by_username($pseudo);
        if ($member) {
            if (!self::same_hash($password, $member->hash_password)) {
                $errors[] = "Mauvais MdP. Veuillez réessayer.";
            }
        } else {
            $errors[] = "Membre '$pseudo' introuvable. Inscrivez-vous :).";
        }
        return $errors;
    }

    public static function validate_passwords($password, $password_confirm) {
        $errors = [];
        if ($password != $password_confirm) {
            $errors[] = "Les mots de passes doivent être identiques!";
        }
        return $errors;
    }

    public static function same_hash($clear_password, $hash) {
        return $hash === Tools::my_hash($clear_password);
    }

    public static function get_all_user() {
        $results = [];
        try {
            $members = self::execute("SELECT * FROM user", array());
            $query = $members->fetchAll();
            foreach ($query as $row) {
                $results[] = new User($row["id"], $row["username"], $row["password"], $row["fullname"], $row["email"], $row["birthdate"], $row["role"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(allusers)");
        }
    }

    public static function get_user_by_role($role) {
        $results = [];
        try {
            $members = self::execute("SELECT * FROM user WHERE role = :role", array("role" => $role));
            $query = $members->fetchAll();
            foreach ($query as $row) {
                $results[] = new User($row["id"], $row["username"], $row["password"], $row["fullname"], $row["email"], $row["birthdate"], $row["role"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(byrole)");
        }
    }

    public static function get_user_by_id($id) {
        try {
            $query = self::execute("SELECT * FROM user WHERE id=:id", array("id" => $id));
            $member = $query->fetch();
            return new User($member["id"], $member["username"], $member["password"], $member["fullname"], $member["email"], $member["birthdate"], $member["role"]);
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données(userbyid)");
        }
    }

    public static function get_user_by_username($username) {
        try {
            $query = self::execute("SELECT * FROM user WHERE username=:username", array("username" => $username));
            $member = $query->fetch();
            return new User($member["id"], $member["username"], $member["password"], $member["fullname"], $member["email"], $member["birthdate"], $member["role"]);
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données(userbyusername)");
        }
    }
    
      public static function get_user_by_password($password) {
        try {
            $query = self::execute("SELECT * FROM user WHERE password=:password", array("password" => Tools::my_hash($password)));
            $member = $query->fetch();
            return new User($member["id"], $member["username"], $member["password"], $member["fullname"], $member["email"], $member["birthdate"], $member["role"]);
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données(userbyusername)");
        }
    }

    public static function get_email_by_id($id) {
        try {
            $query = self::execute("SELECT email FROM user WHERE id=:id", array("id" => $id));
            $email = $query->fetch();
            return $email[0];
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données69");
        }
    }

    public static function get_user_by_mail($email) {
        try {
            $query = self::execute("SELECT * FROM user WHERE email=:email", array("email" => $email));
            return $query->fetch();
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(userbymail)");
        }
    }

    public function is_admin() {
        return $this->role === "admin";
    }

    public function is_manager() {
        return $this->role === "manager";
    }

    public function is_member() {
        return $this->role === "member";
    }

    public static function is_username_not_available($username) {
        try {
            $query = self::execute("SELECT * FROM user WHERE username=:username", array("username" => $username));
            $result = $query->fetchAll();
            return count($result) === 0;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(usernameavailable)");
        }
    }

    private static function _email($email) {
        try {
            $query = self::execute("SELECT email FROM user WHERE email=:email", array("email" => $email));
            $result = $query->fetchAll();
            return count($result) >0;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(usernameavailable)");
        }
    }

    public static function is_email_available( $mail) {
        return !self::_email($mail);
    }

    public static function validate_birthdate_add_user($birthdate) {
        $res = self::age($birthdate);
        
        return $res >= 10;
    }

    public static function validate_birthdate_edit_user( $birthdate) {
        $res = self::age($birthdate);
        if ($_POST['role'] === 'member')
            return $res >= 10;
        elseif ($_POST['role'] === 'admin' || $_POST['role'] === 'manager')
            return $res >= 18;
        else
            return FALSE;
    }

    public function delete_user() {
        try {
            self::execute("DELETE FROM rental WHERE  user=:id", array("id" => $this->id));
            self::execute("DELETE FROM user WHERE  id=:id", array("id" => $this->id));
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(deluser)");
        }
    }

    public static function get_password($id) {
        try {
            $query = self::execute("SELECT password FROM user WHERE id=:id", array("id" => $id));
            $password = $query->fetch();
            return $password["password"];
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(getPsw)");
        }
    }

    public function update() {
        try {
            self::execute("UPDATE user SET username = :username, password = :password, fullname = :fullname,"
                    . "email = :email , birthdate = :birthdate, role = :role "
                    . "WHERE id = :id", array("username" => $this->username, "password" => $this->hash_password, "fullname" => $this->fullname,
                "email" => $this->email, "birthdate" => $this->birthdate, "role" => $this->role, "id" => $this->id));
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(update)");
//            echo $e->getMessage();
        }
    }

    public function insert() {
        if (empty($this->birthdate)) {
            $this->birthdate = null;
        }
        try {
            self::execute("INSERT INTO user(username, password, fullname, email, birthdate, role) "
                    . "VALUES(:username, :password, :fullname, :email, :birthdate, :role)", array("username" => $this->username, "password" => $this->hash_password,
                "fullname" => $this->fullname, "email" => $this->email,
                "birthdate" => $this->birthdate, "role" => $this->role));
            return $this;
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données :" . $ex->getMessage());
        }
    }

    public function get_rental_join_book_join_user_by_user() {
        $results = [];
        try {
            $books = self::execute("SELECT DISTINCT book.*"
                            . "FROM (rental join user on rental.user=user.id) join book on rental.book=book.id "
                            . "WHERE user.id=:id AND rental.rentaldate IS  NULL ", array("id" => $this->id));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"], $row["nbCopies"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(user_by_user)");
        }
    }

    public function get_rental_join_book_join_user_by_user_not_rented() {
        $results = [];
        try {
            $books = self::execute("SELECT DISTINCT book.*"
                            . "FROM (rental join user on rental.user=user.id) join book on rental.book=book.id "
                            . "WHERE user.id=:id AND rentaldate IS NULL", array("id" => $this->id));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"], $row["nbCopies"]);
            }
            return $results;
        } catch (Exception $e) {
            Tools::abort("Problème lors de l'accès a la base de données(user_not_rented)");
        }
    }

    public static function get_username_by_id($id) {
        try {
            $user = self::execute("SELECT username FROM user WHERE id=:id", array("id" => $id));
            $query = $user->fetch();
            return $query[0];
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données(get_username_by_id)");
        }
    }

    public static function get_birthdate($id) {
        try {
            $b = self::execute("SELECT birthdate FROM user WHERE id=:id", array("id" => $id));
            $query = $b->fetch();
            return $query[0];
        } catch (Exception $ex) {
            Tools::abort("Problème lors de l'accès a la base de données(get_username_by_id)");
        }
    }

    public static function errors_add_user($username, $email, $fullname, $password, $password_confirm, $birthdate) {
        $errors = [];
        if (!User::is_username_not_available($username))
            $errors[] = "l'utilisateur existe deja";
        if ( trim($username) == '' || strlen(trim($username)) < 3)
            $errors[] = "Le username est obligatoire(3 caract. min.)";
        if ( trim($fullname) == '')
            $errors[] = "Le fullname est obligatoire";
        if ( trim($email) == '')
            $errors[] = "L'email est obligatoire";
        if (!self::is_email_available($email))
            $errors[] = "l'email existe deja";
        if ($birthdate == "")
            $errors[] = "Date de naissance obligatoire!";
        if (!User::validate_birthdate_edit_user($birthdate))
            $errors[] = "Date de naissance invalide!";
        if ( $password === '' || $password_confirm === '') {
            $errors[] = "Les mdp sont obligatoires!";
        }
        if ($password != $password_confirm)
            $errors[] = "Les mdp doivent être identiques!";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "Email invalide!";
        return $errors;
    }

    public static function errors_edit_profile($member, $oldmail) {
        $error = [];

        if (!isset($_POST["username"]) || $_POST["fullname"] === '')
            $error[] = "Fullname obligatoire!";
        if (strtolower($member->username) !== strtolower($_POST['username']))
            $error = array_merge(self::validate_unicity($_POST["username"]));
        if (strlen($_POST["username"]) < 3 || empty($_POST["username"]))
            $error[] = "Pseudo obligatoir! (min. 3 caractères)";
        if ($member->email !== $oldmail) {
            if (self::is_email_available($member->id, $oldmail)) {
                $error[] = "L'email existe déjà !";
            }
        }
        if (isset($_POST["birthdate"]) && $_POST["birthdate"] !== "")
            if (!User::validate_birthdate_edit_user($member->id, $_POST["birthdate"]))
                $error[] = "Date de naissance invalide!";
        if (!isset($_POST["email"]) || empty($_POST["email"]))
            $error[] = "Email obligatoir!";
        if (isset($_POST["password"]) && ($_POST["password"] !== $_POST["confirm_password"]))
            $error[] = "Les mdp ne correspondent pas!";
        if (!filter_var($oldmail, FILTER_VALIDATE_EMAIL))
            $errors[] = "Email invalide!";
        return $error;
    }

    public static function set_member_attr_edit_profile(&$member, &$confirm_password) {
        if ($_POST["birthdate"] !== "")
            $member->birthdate = $_POST["birthdate"];
        if ($_POST["role"] !== "")
            $member->role = $_POST["role"];
        if ($_POST["fullname"] !== "")
            $member->fullname = $_POST["fullname"];
        if ($_POST["username"] !== "")
            $member->username = $_POST['username'];
        if ($_POST["email"] !== "")
            $member->email = $_POST["email"];
        if (isset($_POST["password"]) && !empty(trim($_POST["password"])))
            $member->hash_password = Tools::my_hash($_POST["password"]);
        if (isset($_POST["confirm_password"]) && !empty(trim($_POST["confirm_password"])))
            $confirm_password = $_POST["confirm_password"];
    }

    public static function is_return_late($datereturn) {
        $return = self::returnDate_one_int($datereturn);
        $today = self::returnDate_one_int(date("d/m/Y"));
        return $today > $return;
    }

    private static function returnDate_one_int($birthdate) {
        $birthdate = explode("/", $birthdate);
        return ($birthdate[2] * 10000) + ($birthdate[1] * 100) + $birthdate[0];
    }

    private static function age($date_naissance) {
        $arr1 = intval($date_naissance);
        $arr2 = intval(date('Y/m/d'));
        return $arr2 - $arr1;
    }
    
    

}
