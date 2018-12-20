<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller {

    //si l'utilisateur est conecté, redirige vers son profil.
    //sinon, produit la vue d'accueil.
    public function index() {
        if ($this->user_logged()) {
            $this->redirect("user", "profil");
        } else {
            (new View("index"))->show();
        }
    }

    //gestion de la connexion d'un utilisateur
    public function login() {
        $pseudo = '';
        $password = '';
        $errors = [];
        if (isset($_POST['pseudo']) && isset($_POST['password'])) { //note : pourraient contenir
        //des chaînes vides
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];
             
            $errors = User::validate_login($pseudo, $password);
            if (empty($errors)) {
                $this->log_user(User::get_user_by_username($pseudo));
            }
        }
        (new View("login"))->show(array("pseudo" => $pseudo, "password" => $password, "errors" => $errors));
    }

    //gestion de l'inscription d'un utilisateur
    public function signup() {
        $username = '';
        $password = '';
        $password_confirm = '';
        $fullname="";
        $email="";
        $birthdate="";
        $role="";
        $id="";
        $errors = [];
        
        $test=User::get_user_by_id(1);
        var_dump($test);
        $test->setUsername("jean");
        $test->update_user();
      
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['birthdate'])) {
            $username = Tools::sanitize(trim($_POST['username']));
            $password = Tools::sanitize($_POST['password']) ;
            $password_confirm = Tools::sanitize($_POST['password_confirm']);
            $fullname= Tools::sanitize($_POST["fullname"]);
            $email= Tools::sanitize($_POST["email"]);
            $birthdate= Tools::sanitize($_POST["birthdate"]);
            $role="member";

            $member = new User($id,$username, Tools::my_hash($password), $fullname, $email, $birthdate, $role);
            $errors = User::validate_unicity($username);
            $errors = array_merge($errors, $member->validate());
            $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));

            if (count($errors) == 0) { 
                $member->insert(); //sauve l'utilisateur
               $this->log_user($member);
            }
        }
        (new View("signup"))->show(array("username" => $username, "password" => $password, "password_confirm" => $password_confirm,"fullname"=>$fullname,"email"=>$email,"birthdate"=>$birthdate, "errors" => $errors));
    }
    
    

}
