<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller {

    //si l'utilisateur est conecté, redirige vers son profil.
    //sinon, produit la vue d'accueil.
    public function index() {
        if (!$this->user_logged()) {
            $this->login();
        } else {
            $this->redirect("user", "profil");
        }
    }

    //gestion de la connexion d'un utilisateur
    public function login() {
        $pseudo = '';
        $password = '';
        $errors = [];
        if (isset($_POST['pseudo']) && isset($_POST['password'])
        ) {
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];

            $errors = User::validate_login($pseudo, $password);
            if (empty($errors)) {
                $this->log_user(User::get_user_by_username($pseudo));
            }
        }
        (new View("index"))->show(array("pseudo" => $pseudo, "password" => $password, "errors" => $errors));
    }

    //gestion de l'inscription d'un utilisateur
    public function signup() {
        $username = '';
        $password = '';
        $password_confirm = '';
        $fullname = "";
        $email = "";
        $birthdate = "";
        $role = "";
        $id = 0;
        $errors = [];
        
    

        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['birthdate'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $fullname = $_POST["fullname"];
            $email = $_POST["email"];
            $birthdate = $_POST["birthdate"];
            $role = "member";
            if(empty($password)|| empty($password_confirm)){
               $errors[] = "Date de naissance invalide!";
            }
            var_dump($password);
            var_dump($password_confirm);
             $member = new User($id, $username, Tools::my_hash($password), $fullname, $email, $birthdate, $role);
            $errors = array_merge( User::validate_unicity($username));
            $errors = array_merge($errors, $member->validate());
            $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors[] = "Email invalide!";
            if (!isset($_POST["birthdate"]) && $birthdate == "")
                $errors[] = "Date de naissance obligatoire!";
            if (!User::validate_birthdate_add_user($_POST["birthdate"]))
                $errors[] = "Date de naissance invalide!";
             
            if (count($errors) == 0) {
                $member->insert();
                $this->log_user(User::get_user_by_username($member->username));
            }
        }
        (new View("signup"))->show(array("username" => $username, "password" => $password, "password_confirm" => $password_confirm, "fullname" => $fullname, "email" => $email, "birthdate" => $birthdate, "errors" => $errors));
    }

}
