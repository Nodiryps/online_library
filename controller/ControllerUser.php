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
        $profile = $this->get_user_or_redirect();
        $returndate = [];
        $datetoreturn = [];
        $userRentals = Rental::get_rentals_by_user($profile->id); // j'ai modifer cet methode car elle cree de beug dans profile (je sai pas pourquoi)

        (new View("profile"))->show(array("profile" => $profile, "rentals" => $userRentals, "returndate" => $returndate));
    }

    public static function is_return_late($datereturn) {
        return strtotime(date("d/m/Y")) > strtotime($datereturn);
    }

    public function user_list() {
        $user = $this->get_user_or_redirect();
        if ($user->is_admin() || $user->is_manager()) {
            $utilisateur = User::get_user_by_username($user->username);
            $id = $utilisateur->id;
            $members = User::get_all_user();
            (new View("user_list"))->show(array("utilisateur" => $utilisateur, "members" => $members, "id" => $id));
        } else
            $this->redirect();
    }

    public function add_user() {
        $utilisateur = $this->get_user_or_redirect();
        if ($utilisateur->is_admin() || $utilisateur->is_manager()) {
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
            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST["fullname"]) && isset($_POST["mail"]) && isset($_POST["birthdate"]) && isset($_POST["role"])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $password_confirm = $_POST['password_confirm'];
                $fullname = $_POST["fullname"];
                $email = $_POST["mail"];
                $birthdate = $_POST["birthdate"];
                $role = $_POST["role"];
                $query = User::get_user_by_username($username);
                $errors = $this->errors_add_user($username, $email, $fullname, $password, $password_confirm);
                $member = new User($id, $username, Tools::my_hash($password), $fullname, $email, $birthdate, $role);
                if (empty($errors)) {
                    $member->insert();
                    $this->redirect("user", "user_list");
                }
            } (new View("add_user"))->show(array("profile" => $utilisateur, "username" => $username, "fullname" => $fullname, "role" => $role, "birthdate" => $birthdate, "errors" => $errors, "email" => $email));
        } else
            $this->redirect();
    }

    private function errors_add_user($username, $email, $fullname, $password, $password_confirm) {
        $errors = [];
        if (!User::is_username_not_available($username))
            $errors[] = "l'utilisateur existe deja";
        if (trim($username) == '')
            $errors[] = "Le username est obligatoire";
        if (strlen(trim($username)) < 3)
            $errors[] = "Le username doit contenir 3 caractères au minimum";
        if (trim($fullname) == '')
            $errors[] = "Le fullname est obligatoire";
        if (trim($email) == '')
            $errors[] = "L'email est obligatoire";
        if (!User::is_email_available($email))
            $errors[] = "l'email existe deja";

        if ($password != $password_confirm && !$password == '' && !$password_confirm = '')
            $errors[] = "Les mots de passe doivent être identiques et non vide";
        return $errors;
    }

    public function edit_profile() {
        $utilisateur = $this->get_user_or_redirect();
        if ($utilisateur->is_admin() || $utilisateur->is_manager()) {
            $tabRoles = ["admin", "manager", "member"];
            $error = [];
            $id = "";
            if (isset($_POST["idmembers"]))
                $id = $_POST["idmembers"];
            $member = User::get_user_by_id($id);
            $members = User::get_all_user();
            if (isset($_POST["username"]) || isset($_POST["fullname"]) || isset($_POST["email"]) || isset($_POST["birthdate"]) || isset($_POST["role"])) {
                $member = User::get_user_by_id($_POST["idmember"]);
                self::set_member_attr($member);
                $error = self::rules_edit_profile($member, $_POST["email"]);
                if (empty($error)) {
                    //$member->update();
//                    if ($utilisateur->id === $member->id)
//                        $_SESSION["user"] = $member;
//                    Controller::redirect("user", "user_list");
                }
            }
            (new View("edit_profile"))->show(array("tabRoles" => $tabRoles, "members" => $members, "member" => $member, "error" => $error, "utilisateur" => $utilisateur));
        } else
            $this->redirect();
    }

    private static function set_member_attr($member) {
        if ($_POST["birthdate"] !== "")
            $member->birthdate = $_POST["birthdate"];
        if ($_POST["role"] !== "")
            $member->role = $_POST["role"];
        if ($_POST["fullname"] !== "")
            $member->fullname = $_POST["fullname"];
        if ($_POST["username"] !== "")
            $member->username = $_POST["username"];
        if ($_POST["email"] !== "")
            $member->email = $_POST["email"];
    }

    private static function is_email_available($id, $newEmail) {
        $emails[] = self::get_all_emails(User::get_email_by_id($id));
        foreach ($emails as $e) {
            if($newEmail !== $e)
            return $newEmail !== $e;
        }
    }

    private static function get_all_emails($curr) {
        $members = User::get_all_user();
        $emails = [];
        foreach ($members as $m) {
            if ($m->email !== $curr)
                $emails[] = User::get_email_by_id($m->id);
        }
        var_dump($emails);
        return $emails;
    }

    private static function rules_edit_profile($member, $email) {
      
        $error = [];
        if ($member->fullname =='') {
              echo "lkdsfjlkdsfj";
            $error[] = "Fullname obligatoire!";
        }
        if (empty($member->role)) {
            $error[] = "Role obligatoir!";
        }
        if (strlen($member->username) < 3 || empty($member->username)) {
            $error[] = "Pseudo obligatoir! (min. 3 caractères)";
        }
        if (!self::is_email_available($member->id, $email)) {
            echo "lkqkdfmlksqd";
            $error[] = "L'email existe déjà !";
        }
        if (empty($email)) {
            $error[] = "Email obligatoir!";
        }
        return $error;
    }

    public function delete_user() {
        $utilisateur = $this->get_user_or_redirect();
        if ($utilisateur->is_admin()) {
            $memberToDelete = "";
            if (isset($_POST["iddelete"]))
                $memberToDelete = User::get_user_by_id($_POST["iddelete"]);

            if (isset($_POST["conf"]) && !empty($_POST["conf"])) {
                $memberToDelete = User::get_user_by_id($_POST["conf"]);
                $memberToDelete->delete_user();
                $this->redirect("user", "user_list");
            }
            (new View("delete_confirm"))->show(array("utilisateur" => $utilisateur, "member" => $memberToDelete));
        } else
            $this->redirect();
    }

    public function rentals_by_user($user) {
        return Rental::get_rentals_by_user($user);
    }

    private function Validate_email($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'test reussi';
        } else {
            echo" poulouou";
        }
    }

}
