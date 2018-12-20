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

        $user = Controller::get_user_or_redirect();

        $utilisateur = User::get_user_by_username($user->username);

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
        $error = [];
        $confirm_password = "";
        $user = Controller::get_user_or_redirect();
        $utilisateur = User::get_user_by_username($user->username);
        if (Tools::issets("idmember"))
            $id = Tools::post("idmember");
        var_dump($utilisateur);
        $oldpass = User::get_password($id);
        $member = User::get_user_by_id($id);
        $username = $member->username;
        $fullname = $member->fullname;
        $email = $member->email;
        $birthdate = $member->birthdate;
        $role = $member->role;
        $password = $member->hash_password;

        if (Tools::issets("username") || Tools::issets("fullname") || Tools::issets("email") || Tools::issets("birthdate") || Tools::issets("role") || Tools::issets("password") || Tools::issets("confirm_password")) {
            $username = $member->username;
            $fullname = $member->fullname;
            $email = $member->email;
            $birthdate = $member->birthdate;
            $role = $member->role;
            $member->hash_password;
            $confirm_password = Tools::sanitize(Tools::post("confirm_password"));

            if (Tools::issets("birthdate") && Tools::post("birthdate") !== "")
                $member->birthdate = Tools::sanitize(Tools::post("birthdate"));
            if (Tools::issets("role") && Tools::post("role") !== "")
                $member->role = Tools::post("role");
            if (Tools::issets("username") && Tools::post("username") !== "")
                $member->username = Tools::sanitize(Tools::post("username"));
            else
                $error[] = "Il faut indiquer un username!";
            if (Tools::issets("email") && Tools::post("email") !== "")
                $member->email = Tools::sanitize(Tools::post("email"));
            else
                $error[] = "Il faut indiquer un email!";
            if (Tools::issets("password") && empty(trim(Tools::post("password"))))
                $member->hash_password = Tools::sanitize(Tools::post("password"));
            if (Tools::issets("confirm_password") && Tools::post("confirm_password") !== "")
                $confirm_password = Tools::sanitize(Tools::post("confirm_password"));
            if (Tools::issets("email") && Tools::post("email") !== "")
                $member->fullname = Tools::sanitize(Tools::post("fullname"));
            if (empty($member->fullname))
                $error[] = "Le champ \"fullname\" est obligatoire!";
            if (empty($member->role))
                $error[] = "Il faut indiquer un role!";
            if (strlen($member->username) < 3)
                $error[] = "Le username doit faire plus de 3 caractères";
            if ($member->hash_password !== $confirm_password) {
                $error[] = "Les mots de passe ne correspondent pas!";
            }

            if (!User::check_password($member->hash_password, $oldpass) && !empty($password)) {
                $oldpass = $member->hash_password;
                var_dump($oldpass);
            } else {
                $member->hash_password = $oldpass;
            }



            if (empty($error)) {
                try {
                    $member->update_user();
                    if ($utilisateur->id === $member->id) {
                        $_SESSION["user"] = $username;
                    }
                    Controller::redirect("user", "user_list");
                } catch (Exception $exc) {
                    // die("problemes lors de l'acces a la base de donnée");
//                     echo  $exc->getCode(); echo 666;
//                     echo  $exc->getFile();echo 666;
//                     echo  $exc->getLine();echo 666;
//                     echo  $exc->getMessage();echo 666;
//                     echo  $exc->getPrevious();echo 666;
//                     echo  $exc->getTraceAsString();echo 666;
                }
            } else {
                
            }
        }
        (new View("edit_profile"))->show(array("username" => $username, "password" => $password, "email" => $email, "role" => $role, "birthdate" => $birthdate, "fullname" => $fullname, "member" => $member, "id" => $id, "error" => $error, "utilisateur" => $utilisateur));
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
