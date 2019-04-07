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
        $vignette= count(Rental::get_rentals_by_user($profile->id));
        (new View("profile"))->show(array("profile" => $profile, "rentals" => $userRentals, "returndate" => $returndate,"vignette"=>$vignette));
    }

    public static function is_return_late($datereturn) {
        return date("d/m/Y") > $datereturn;
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
            if (isset($_POST['username']) || isset($_POST['password']) || isset($_POST['password_confirm']) || isset($_POST["fullname"]) || isset($_POST["mail"]) || isset($_POST["birthdate"]) || isset($_POST["role"])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $password_confirm = $_POST['password_confirm'];
                $fullname = $_POST["fullname"];
                $email = $_POST["mail"];
                $birthdate = $_POST["birthdate"];
                $role = "member";
                $query = User::get_user_by_username($username);
                $errors = User::errors_add_user($username, $email, $fullname, $password, $password_confirm,$birthdate);
                $member = new User($id, $username, Tools::my_hash($password), $fullname, $email, $birthdate, $role);
                if (empty($errors)) {
                    $member->insert();
                    $this->redirect("user", "user_list");
                }
            } (new View("add_user"))->show(array("profile" => $utilisateur, "username" => $username, "fullname" => $fullname, "birthdate" => $birthdate, "role" => $role, "errors" => $errors, "email" => $email));
        } else
            $this->redirect();
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
                User::set_member_attr_edit_profile($member, $confirm_password);
                $error = User::errors_edit_profile($member, $_POST["email"]);
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
    
    public function delete_user() {
        $utilisateur = $this->get_user_or_redirect();
        if ($utilisateur->is_admin()) {
            $memberToDelete = "";
            if (isset($_POST["iddelete"])) {
                $memberToDelete = User::get_user_by_id($_POST["iddelete"]);
            }

            if (isset($_POST["conf"]) && !empty($_POST["conf"])) {
                $memberToDelete = User::get_user_by_id($_POST["conf"]);
                $memberToDelete->delete_user();
                $this->redirect("user", "user_list");
            }
            (new View("delete_confirm"))->show(array("utilisateur" => $utilisateur, "member" => $memberToDelete));
        } else {
            $this->redirect();
        }
    }

    public function rentals_by_user($user) {
        return Rental::get_rentals_by_user($user);
    }
}
