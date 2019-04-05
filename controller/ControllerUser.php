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
                echo "allooooooooooooooooo?";
                $username = $_POST['username'];
                $password = $_POST['password'];
                $password_confirm = $_POST['password_confirm'];
                $fullname = $_POST["fullname"];
                $email = $_POST["mail"];
                $birthdate = $_POST["birthdate"];
                $role = "member";
                $query = User::get_user_by_username($username);
                $errors = self::errors_add_user($username, $email, $fullname, $password, $password_confirm);
                $member = new User($id, $username, Tools::my_hash($password), $fullname, $email, $birthdate, $role);
                if (empty($errors)) {
                    $member->insert();
                    $this->redirect("user", "user_list");
                }
            } if (!(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST["fullname"]) && isset($_POST["mail"]) && isset($_POST["birthdate"]) && isset($_POST["role"])))
                $errors[] = "TOUS les champs doivent être complétés";
            (new View("add_user"))->show(array("profile" => $utilisateur, "username" => $username, "fullname" => $fullname, "birthdate" => $birthdate, "role" => $role, "errors" => $errors, "email" => $email));
        } else
            $this->redirect();
    }

    private static function errors_add_user($username, $email, $fullname, $password, $password_confirm) {
        $errors = [];
        echo "errooooooors";
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
        if (isset($_POST["birthdate"]) && $_POST["birthdate"] !== "")
            if (!User::validate_birthdate_add_user($_POST["birthdate"]))
                $error[] = "Date de naissance invalide!";
        if ($password != $password_confirm && !$password == '' && !$password_confirm = '')
            $errors[] = "Les mots de passe doivent être identiques et non vide";
        return $errors;
    }

    public function edit_profile() {
        $utilisateur = $this->get_user_or_redirect();
        if ($utilisateur->is_admin() || $utilisateur->is_manager()) {
            $tabRoles = ["admin", "manager", "member"];
            $confirm_password = "";
            $error = [];
            $id = "";
            $members = User::get_all_user();
            if (isset($_POST["idmembers"]))
                $id = $_POST["idmembers"];
            $oldpass = User::get_password($id);
            $oldmail = User::get_email_by_id($id);
            $member = User::get_user_by_id($id);
            if (isset($_POST["username"]) || isset($_POST["fullname"]) || isset($_POST["email"]) || isset($_POST["birthdate"]) || isset($_POST["role"]) || isset($_POST["password"]) || isset($_POST["confirm_password"])) {
                $oldpass = User::get_password($_POST["idmember"]);
                $member = User::get_user_by_id($_POST["idmember"]);
                echo $member->role;
                echo substr((User::today_one_int() - User::birthdate_one_int($_POST["birthdate"])), 0, 2);
                echo User::today_one_int() - User::birthdate_one_int($_POST["birthdate"]);
                self::set_member_attr($member, $confirm_password);
                $error = self::errors_edit_profile($member, $oldmail);
                if (empty($error)) {
                    $member->update();
                    if ($utilisateur->id === $member->id)
                        $_SESSION["user"] = $member;
                    Controller::redirect("user", "user_list");
                }
            }
            (new View("edit_profile"))->show(array("tabRoles" => $tabRoles, "members" => $members, "member" => $member, "error" => $error, "utilisateur" => $utilisateur));
        } else
            $this->redirect();
    }

    private static function errors_edit_profile($member, $oldmail) {
        $error = [];
        if ($_POST["fullname"] === '')
            $error[] = "Fullname obligatoire!";
        if (strlen($_POST["username"]) < 3 || empty($_POST["username"]))
            $error[] = "Pseudo obligatoir! (min. 3 caractères)";
        if (!self::is_email_available($member->id, $_POST["email"], $oldmail))
            $error[] = "L'email existe déjà !";
        if (isset($_POST["birthdate"]) && $_POST["birthdate"] !== "")
            if (!User::validate_birthdate_edit_user($member->id, $_POST["birthdate"]))
                $error[] = "Date de naissance invalide!";
        if (empty($_POST["email"]))
            $error[] = "Email obligatoir!";
        if (isset($_POST["password"]) && $_POST["password"] !== $_POST["confirm_password"])
            $error[] = "Les mots de passe ne correspondent pas!";
        return $error;
    }

    private static function set_member_attr(&$member, &$confirm_password) {
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
        if (isset($_POST["password"]) && !empty(trim($_POST["password"])))
            $member->hash_password = Tools::my_hash($_POST["password"]);
        if (isset($_POST["confirm_password"]) && !empty(trim($_POST["confirm_password"])))
            $confirm_password = $_POST["confirm_password"];
    }

    private static function is_email_available($id, $newEmail, $oldmail) {
        var_dump("old: " . $oldmail . "\n");
        var_dump("new: " . $newEmail);
        if ($oldmail !== $newEmail) {
            $emails[] = self::get_all_emails(User::get_email_by_id($id));
            foreach ($emails as $e) {
                if ($newEmail !== $e)
                    return $newEmail !== $e;
            }
        } return FALSE;
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
