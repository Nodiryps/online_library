<?php

require_once 'model/User.php';
require_once 'model/Rental.php';
require_once 'ControllerBook.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerUser extends Controller {

    public function index() {
        $this->profil();
    }

    public function profil() {
        $profile = self::get_user_or_redirect();
        $returndate = [];
        $datetoreturn = [];
        $userRentals = Rental::get_rentals_by_user($profile->id); // j'ai modifer cet methode car elle cree de beug dans profile (je sai pas pourquoi)

        (new View("profile"))->show(array("profile" => $profile, "rentals" => $userRentals, "returndate" => $returndate));
    }

    public static function gestion_date($datereturn) {
        return strtotime(date("d/m/Y")) > strtotime($datereturn);
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
        $role = "";
        $errors = [];

        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) &&
                isset($_POST["fullname"]) && isset($_POST["mail"]) && isset($_POST["birthdate"]) && isset($_POST["role"])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $fullname = $_POST["fullname"];
            $email = $_POST["mail"];
            $birthdate = $_POST["birthdate"];
            $role = $_POST["role"];
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
            if (empty($errors)) {
                $member->insert();
            }
        }
        (new View("add_user"))->show(array("profile" => $utilisateur, "username" => $username, "fullname" => $fullname, "role" => $role, "birthdate" => $birthdate, "errors" => $errors));
    }

    public function edit_profile() {
        $error = [];
        $confirm_password = "";
        $utilisateur = Controller::get_user_or_redirect();
        $id = "";
        if (isset($_POST["idmember"]))
            $id = $_POST["idmember"];
        $oldpass = User::get_password($id);
        $member = User::get_user_by_id($id);
        $members = User::get_all_user();

        if (isset($_POST["username"]) || isset($_POST["fullname"]) || isset($_POST["email"]) ||
                isset($_POST["birthdate"]) || isset($_POST["role"]) || isset($_POST["password"]) ||
                isset($_POST["confirm_password"])) {
//            $member->username = $_POST["username"];
//            $member->fullname = $_POST["fullname"];
//            $member->email = $_POST["email"];
//            $member->birthdate = $_POST["birthdate"];
//            $member->role = $_POST["role"];
//            $confirm_password = $_POST["confirm_password"];
            var_dump($_POST["username"]);
            if (isset($_POST["birthdate"]) && $_POST["birthdate"] !== "")
                $member->birthdate = $_POST["birthdate"];
            if (isset($_POST["role"]) && $_POST["role"] !== "")
                $member->role = $_POST["role"];
            if (isset($_POST["username"]) && $_POST["username"] !== "")
                $member->username = $_POST["username"];
            else
                $error[] = "Il faut indiquer un username!";
            if (isset($_POST["email"]) && $_POST["email"] !== "")
                $member->email = $_POST["email"];
            else
                $error[] = "Il faut indiquer un email!";
            if (isset($_POST["password"]) && empty(trim($_POST["password"])))
                $member->hash_password = $_POST["password"];
            if (isset($_POST["confirm_password"]) && $_POST["confirm_password"] !== "")
                $confirm_password = $_POST["confirm_password"];
            if (isset($_POST["fullname"]) && $_POST["fullname"] !== "")
                $member->fullname = $_POST["fullname"];
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
            } else {
                $member->hash_password = $oldpass;
            }
            if (empty($error)) {

                $member->update();
                if ($utilisateur->id === $member->id) {

                    $_SESSION["user"] = $member;
                }
                Controller::redirect("user", "user_list");
            }else{
                echo "qjdfhsdjf";
            }
        }
        (new View("edit_profile"))->show(array("members" => $members, "member" => $member, "error" => $error, "utilisateur" => $utilisateur));
    }

    private function rules_edit_profile($member, $fullname, $email, $role, $username, $password, $confirm_password) {
        $errors = [];
        if (trim($fullname) === "")
            $errors[] = "Le champ \"fullname\" est obligatoire!";
        if (trim($_POST[$username]) === "" && trim($_POST[$username]) < 3)
            $errors[] = "Le pseudo est obligatoire (3 min.)";
        if (trim($_POST[$password]) !== trim($_POST[$confirm_password]))
            $errors[] = "Les mots de passe ne correspondent pas!";
        if (trim($_POST[$password]) === "" || trim($_POST[$confirm_password]) === "")
            $errors[] = "Mot de passe obligatoire!";
        if ($_POST[$role] === "")
            $errors[] = "Role invalide!";
        if ($_POST[$email] === "")
            $errors[] = "Il faut indiquer un email!";

        if (!(isset($_POST["username"]) && $_POST["username"] !== ""))
            $errors[] = "Il faut indiquer un username!";
        if (!(isset($_POST["email"]) && $_POST["email"] !== ""))
            $errors[] = "Il faut indiquer un email!";

        if (empty($member->fullname))
            $errors[] = "Le champ \"fullname\" est obligatoire!";
        if (empty($member->role))
            $errors[] = "Il faut indiquer un role!";
        if (strlen($member->username) < 3)
            $errors[] = "Le username doit faire plus de 3 caractères";
        if ($member->hash_password !== trim($_POST[$confirm_password]))
            $errors[] = "Les mots de passe ne correspondent pas!";
        return $errors;
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

    public function rentals_by_user($user) {
        return Rental::get_rentals_by_user($user);
    }

}
