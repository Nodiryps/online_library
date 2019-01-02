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
require_once 'model/rental.php';

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
        }
        return $errors;
    }

    public static function validate_unicity($pseudo) {
        $errors = [];
        $member = self::is_username_not_available($pseudo);
        if (!$member) {
            $errors[] = "This user already exists.";
        }
        return $errors;
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_login($pseudo, $password) {
        $errors = [];
        $member = self::get_user_by_username($pseudo);
        if ($member) {
            if (!self::check_password($password, $member->hash_password)) {
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
            $errors[] = "Les MdP doivent être identiques!";
        }
        return $errors;
    }

    public static function check_password($clear_password, $hash) {
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
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_user_by_id($id) {
        try {
            $query = self::execute("SELECT * FROM user WHERE id=:id", array("id" => $id));
            $member = $query->fetch();
            return new User($member["id"], $member["username"], $member["password"], $member["fullname"], $member["email"], $member["birthdate"], $member["role"]);
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_user_by_username($username) {
        try {
            $query = self::execute("SELECT * FROM user WHERE username=:username", array("username" => $username));
            $member = $query->fetch();
            return new User($member["id"], $member["username"], $member["password"], $member["fullname"], $member["email"], $member["birthdate"], $member["role"]);
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données");
            return false;
        }
    }

    public static function get_email_by_id($id) {
        try {
            $query = self::execute("SELECT email FROM user WHERE id=:id", array("id" => $id));
            return $email = $query->fetch();
        } catch (Exception $e) {
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_user_by_mail($email) {
        try {
            $query = self::execute("SELECT * FROM user WHERE email=:email", array("email" => $email));
            return $email = $query->fetch();
        } catch (Exception $e) {
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function is_username_not_available($username) {
        try {
            $query = self::execute("SELECT * FROM user WHERE username=:username", array("username" => $username));
            $result = $query->fetchAll();
            return count($result) === 0;
        } catch (Exception $e) {
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function is_email_available($email) {
        try {
            $query = self::execute("SELECT email FROM user WHERE email=:email", array("email" => $email));
            $result = $query->fetchAll();
            return count($result) === 0;
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function add_user($username, $password, $fullname, $email, $birthdate) {
        $role = "member";
//        $query = "";
        try {
            $query = self::execute("INSERT INTO user(username,password,fullname,email,birthdate,role)
                                        VALUES(:username,:password,:fullname,:email,:birthdate,:role)", array(
                        "username" => $username,
                        "password" => my_hash($password),
                        "fullname" => $fullname,
                        "email" => $email,
                        "birthdate" => $birthdate,
                        "role" => $role
            ));
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function admin_add_user($username, $password, $fullname, $email, $birthdate, $role) {
        try {
            $query = self::execute("INSERT INTO user(username,password,fullname,email,birthdate,role)
                                        VALUES(:username,:password,:fullname,:email,:birthdate,:role)", array(
                        "username" => $username,
                        "password" => my_hash($password),
                        "fullname" => $fullname,
                        "email" => $email,
                        "birthdate" => $birthdate,
                        "role" => $role
            ));
        } catch (Exception $e) {
            Tools:: abort("Problème lors de l'accès a la base de données");
        }
    }

    public function delete_user() {
        try {
            self::execute("DELETE FROM user WHERE  id=:id", array("id" => $this->id));
        } catch (Exception $e) {
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_password($id) {

        try {
            $query = self::execute("SELECT password FROM user WHERE id=:id", array("id" => $id));
            $password = $query->fetch();
            return $password["password"];
        } catch (Exception $e) {
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

//
    public static function get_role_by_id($id) {
        try {
            $query = self::execute("SELECT role FROM user WHERE id=:id", array("id" => $id));
            $role = $query->fetch();
            return $role["role"];
        } catch (Exception $e) {
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function update() {
        $birthdate = $this->birthdate;
        if($birthdate === "")
            $birthdate = NULL;
        
        if (!self::is_username_not_available($this->username))
            self::execute("UPDATE user SET username = :username, password = :password, fullname = :fullname,"
                        . "email = :email , birthdate = :birthdate, role = :role "
                        . "WHERE username = :username", 
                    array("username" => $this->username, "password" => $this->hash_password, "fullname" => $this->fullname,
                        "email" => $this->email , "birthdate" => $this->birthdate, "role" => $this->role));
    }

    public function insert() {
        try {
            self::execute("INSERT INTO user(username, password, fullname, email, birthdate, role) "
                        . "VALUES(:username, :password, :fullname, :email, :birthdate, :role)", 
                    array("username" => $this->username, "password" => $this->hash_password,
                          "fullname" => $this->fullname, "email" => $this->email,
                          "birthdate" => $this->birthdate, "role" => $this->role));
            return $this;
        } catch (Exception $ex) {
             Tools::abort("Problème lors de l'accès a la base de données");
        }
    }

    public function get_rental_join_book_join_user_by_user() {
        $results = [];
        try {
            $books = self::execute("select DISTINCT book.id,book.isbn,book.title,book.author,book.editor,book.picture FROM (rental join user on rental.user=user.id) join book on rental.book=book.id where user.id=:id AND rental.rentaldate IS  NULL ", array("id" => $this->id));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"]);
            }
            return $results;
            return $query;
        } catch (Exception $e) {
           // Tools::abort("Problème lors de l'accès a la base de données");
            echo $e->getMessage();
        }
    }
    
    public function get_rental_join_book_join_user_by_user_not_rented(){
        $results = [];
        try {
            $books = self::execute("select DISTINCT book.id,book.isbn,book.title,book.author,book.editor,book.picture FROM (rental join user on rental.user=user.id) join book on rental.book=book.id where user.id=:id AND rentaldate IS NULL", array("id" => $this->id));
            $query = $books->fetchAll();
            foreach ($query as $row) {
                $results[] = new Book($row["id"], $row["isbn"], $row["title"], $row["author"], $row["editor"], $row["picture"]);
            }
            return $results;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
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
}
