<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser extends Controller {

    public function index() {
        $this->profil();
    }

    public function profil() {
        $profile = self::get_user_or_redirect();
        (new View("profile"))->show(array("profile" => $profile));
    }

    public function user_list() {

        $utilisateur = self::get_user_or_redirect();
        $id = $utilisateur->id;
        $members = User::get_all_user();


        (new View("user_list"))->show(array("utilisateur" => $utilisateur, "members" => $members, "id" => $id));
    }

    public function add_user() {
        $utilisateur = self::get_user_or_redirect();
        $username = '';
        $password = '';
        $password_confirm = '';
        $fullname = "";
        $email = "";
        $birthdate = "";
        $query = "";
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) &&
                isset($_POST["fullname"]) && isset($_POST["mail"]) && isset($_POST["birthdate"]) && isset($_POST["role"])) {
            $username = sanitize($_POST['username']);
            $password = sanitize($_POST['password']);
            $password_confirm = sanitize($_POST['password_confirm']);
            $fullname = sanitize($_POST["fullname"]);
            $email = sanitize($_POST["mail"]);
            $birthdate = sanitize($_POST["birthdate"]);
            $role = sanitize($_POST["role"]);

            /////////////////////////////
            $query = get_user_by_username($username);


            if (!is_username_available($username))
                $errors[] = "l'utilisateur existe deja";


            if (trim($username) == '')
                $errors[] = "Le username est obligatoire";
            if (strlen(trim($username)) < 3)
                $errors[] = "Le username doit contenir 3 caractères au minimum";
            if ($password != $password_confirm)
                $errors[] = "Les mots de passe doivent être identiques";

            if (!isset($errors)) {
                try {
                    User::admin_add_user($username, $password, $fullname, $email, $birthdate, $role);
                    redirect("userList.php");
                } catch (Exception $ex) {
                    abort("Problème lors de l'accès a la base de données");
                    $ex->getMessage();
                }



                (new View("add_user"))->show(array("utilisateur" => $utilisateur));
            }
            (new View("add_user"))->show(array("utilisateur" => $utilisateur));
        }
}

                }
      