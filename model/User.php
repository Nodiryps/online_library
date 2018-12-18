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

class User extends Model {

    var $id;
    var $username;
    var $password;
    var $fullname;
    var $email;
    var $birthdate;
    var $role;

    public function __construct($username, $password, $fullname, $email, $birthdate, $role) {
        $this->username = $username;
        $this->password = $password;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->role = $role;
    }

    function get_user($id) {
        return sql_fetch("SELECT * FROM user where id = :id", array("id" => $id));
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
        $member = self::get_user_by_username($pseudo);
        if ($member) {
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
            if (!self::check_password($password, $member["password"])) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a member with the pseudo '$pseudo'. Please sign up.";
        }
        return $errors;
    }

    public static function validate_passwords($password, $password_confirm) {
      ;
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    private static function check_password($clear_password, $hash) {
//        return $hash === Tools::my_hash($clear_password);
        return $hash === $clear_password;
    }

    public static function get_all_user() {

        try {
            $query = self::execute("SELECT * FROM user", array());
            return $members = $query->fetchAll();
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_user_by_id($id) {
        try {
            $query = self::execute("SELECT * FROM user WHERE id=:id", array("id" => $id));
            return $member = $query->fetch();
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_user_by_username($username) {

        try {
            $member = self::execute("SELECT * FROM user WHERE username=:username", array("username" => $username));
            return $member->fetch();
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_email_by_id($id) {

        try {
            $query = self::execute("SELECT email FROM user WHERE id=:id", array("id" => $id));
            return $email = $query->fetch();
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

//
    public static function get_user_by_mail($email) {
        try {
            $query = self::execute("SELECT * FROM user WHERE email=:email", array("email" => $email));
            return $email = $query->fetch();
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function is_username_available($username) {

        try {
            $query = self::execute("SELECT * FROM user WHERE username=:username", array("username" => $username));
            $result = $query->fetchAll();
            return count($result) === 0;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function is_email_available($email) {

        try {
            $query = self::execute("SELECT email FROM user WHERE email=:email", array("email" => $email));
            $result = $query->fetchAll();
            return count($result) === 0;
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function add_user($username, $password, $fullname, $email, $birthdate) {

        $role = "member";
        $query = "";
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
            abort("Problème lors de l'accès a la base de données");
        }
    }

//
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
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function update_user($id, $username, $password, $fullname, $email, $birthdate, $role) {

        try {
            $query = self::execute("UPDATE user SET username=:username ,password=:password,fullname=:fullname,email=:email,birthdate=:birthdate,role=:role WHERE id=:id", array(
                        "id" => $id,
                        "username" => $username,
                        "password" => my_hash($password),
                        "fullname" => $fullname,
                        "email" => $email,
                        "birthdate" => $birthdate,
                        "role" => $role
            ));
        } catch (Exception $e) {
            Tools::abort("Problèmez lors de l'accès a la base de données");
            $e->getCode();
            $e->getLine();
            $e->getTraceAsString();
        }
    }

//
    public static function delete_user($id) {

        try {
            $query = self::execute("DELETE FROM user WHERE  id=:id", array("id" => $id));
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

    public static function get_password($id) {

        try {
            $query = self::execute("SELECT password FROM user WHERE id=:id", array("id" => $id));

            $password = $query->fetch();
            return $password["password"];
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }

//
    public static function get_role_by_id($id) {

        try {
            $query = self::execute("SELECT role FROM user WHERE id=:id", array("id" => $id));
            $role = $query->fetch();
            return $role["role"];
        } catch (Exception $e) {
            abort("Problème lors de l'accès a la base de données");
        }
    }
    
      public function update() {
        if(self::get_user_by_username($this->username))
            self::execute("UPDATE user SET password=:password, picture_path=:picture, profile=:profile WHERE pseudo=:pseudo ", 
                          array("picture"=>$this->picture_path, "profile"=>$this->profile, "pseudo"=>$this->pseudo, "password"=>$this->hashed_password));
        else
            self::execute("INSERT INTO Members(pseudo,password,profile,picture_path) VALUES(:pseudo,:password,:profile,:picture_path)", 
                          array("pseudo"=>$this->pseudo, "password"=>$this->hashed_password, "picture_path"=>$this->picture_path, "profile"=>$this->profile));
        return $this;
    }

}
