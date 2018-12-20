<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

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

        $id = "";
        $username = '';
        $password = '';
        $password_confirm = '';
        $fullname = "";
        $email = "";
        $birthdate = "";
        $query = "";
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) &&
                isset($_POST["fullname"]) && isset($_POST["mail"]) && isset($_POST["birthdate"]) && isset($_POST["role"])) {
            $username = Tools::sanitize($_POST['username']);
            $password = Tools::sanitize($_POST['password']);
            $password_confirm = Tools::sanitize($_POST['password_confirm']);
            $fullname = Tools::sanitize($_POST["fullname"]);
            $email = Tools::sanitize($_POST["mail"]);
            $birthdate = Tools::sanitize($_POST["birthdate"]);
            $role = Tools::sanitize($_POST["role"]);

            /////////////////////////////
            $query = User::get_user_by_username($username);


            if (!User::is_username_not_available($username))
                $errors[] = "l'utilisateur existe deja";


            if (trim($username) == '')
                $errors[] = "Le username est obligatoire";
            if (strlen(trim($username)) < 3)
                $errors[] = "Le username doit contenir 3 caractères au minimum";
            if ($password != $password_confirm)
                $errors[] = "Les mots de passe doivent être identiques";


            $member = new User($id, $username, Tools::my_hash($password), $fullname, $email, $birthdate, $role);
            if (!isset($errors)) {
                try {
//User::admin_add_user($username, $password, $fullname, $email, $birthdate, $role);
                    $member->insert();
                } catch (Exception $ex) {
                    abort("Problème lors de l'accès a la base de données");
                    $ex->getMessage();
                    $ex->getLine();
                }
            }
        }
        (new View("add_user"))->show(array("utilisateur" => $utilisateur, "username" => $username, "fullname" => $fullname, "role" => $role, "birthdate" => $birthdate));
    }

    public function edit_profile() {
        $id = "";
        $error = [];
        $utilisateur = Controller::get_user_or_redirect();
        
        if (Tools::issets("idmember"))
            $id = Tools::post("idmember");
        $oldpass = User::get_password($id);
       
        $member = User::get_user_by_id($id);
        
        if (isset($_POST["username"]) || isset($_POST["fullname"]) && !empty($_POST["fullname"]) || isset($_POST["email"]) || Tools::issets("birthdate") || Tools::issets("role") || Tools::issets("password") || Tools::issets("confirm_password")) {
            $idmember = $member->id;
            $member->fullname = Tools::sanitize(Tools::post("fullname"));
            $member->username=$_POST["username"];
            $birthdate = "0000-00-00";
            $role = User::get_role_by_id($idmember);
            $password = Tools::sanitize(Tools::post("password"));
            $confirm_password = Tools::sanitize(Tools::post("confirm_password"));



            if (Tools::issets("birthdate") && Tools::post("birthdate") !== "")
               $member->birthdate=Tools::post("birthdate");

            if (Tools::issets("role") && Tools::post("role") !== "")
                $member->role= Tools::post("role");


            if (Tools::issets("username") &&Tools:: post("username") !== "")
                $member->username = Tools::sanitize(Tools::post("username"));
            else
                $error[] = "Il faut indiquer un username!";


            if (Tools::issets("email") &&Tools::post("email") !== "")
                $member->email = Tools::sanitize(Tools::post("email"));
            else
                $error[] = "Il faut indiquer un email!";


            if (Tools::issets("password") && empty(trim(Tools::post("password"))))
                $member->hash_password= Tools::sanitize(Tools::post("password"));


            if (Tools::issets("confirm_password") && Tools::post("confirm_password") !== "")
                $confirm_password = Tools::sanitize(Tools::post("confirm_password"));


            if (empty($fullname))
                $error[] = "Le champ \"fullname\" est obligatoire!";

            if (empty($role))
                $error[] = "Il faut indiquer un role!";

            if (strlen($username) < 3)
                $error[] = "Le username doit faire plus de 3 caractères";



            if ($password !== $confirm_password) {
                $error[] = "Les mots de passe ne correspondent pas!";
            }

            if (!User::check_password($password, $oldpass) && !empty($password)) {
                $oldpass = $password;
            } else {
                $password = $oldpass;
            }



            if (empty($error)) {
                try {
                    var_dump($member);
                    $member->update_user();
                    
                    if ($utilisateur->id === $member->id) {
                        $_SESSION["user"] = $username;
                    }
                   // Controller::redirect("user", "user_list");
                } catch (Exception $exc) {
                    die("problemes lors de l'acces a la base de donnée");
                }
            } 
        }
        (new View("edit_profile"))->show(array("member" => $member, "id" => $id, "error" => $error, "utilisateur" => $utilisateur));
    }

    public function delete_user() {
        $utilisateur = self::get_user_or_redirect();
        $id = "";
        $memberToDelete = "";

        if (isset($_POST["iddelete"])) {
            $id = $_POST["iddelete"];
            $memberToDelete = User::get_user_by_id($id);
        }
        if (isset($_POST["conf"]) && !empty($_POST["conf"])) {
            $id = $_POST["conf"];
            $memberToDelete = User::get_user_by_id($id);
            $memberToDelete->delete_user();
            Controller::redirect("user", "user_list");
        }
        (new View("delete_confirm"))->show(array("utilisateur" => $utilisateur, "member" => $memberToDelete));
    }

}
