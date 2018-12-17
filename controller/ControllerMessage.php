<?php

require_once 'model/Member.php';
require_once 'model/Message.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMessage extends Controller {

    //accueil du controlleur.
    //gère l'affichage des messages et le post
    public function index() {
        $user = $this->get_user_or_redirect();
        $recipient = $this->get_recipient($user);
        $errors = [];
        //le code suivant est exécuté si javascript n'est pas activé.
        //(le formulaire en question ne POST rien si JS est activé)
        if (isset($_POST['body'])) {
            $errors = $this->post($user, $recipient);
        }

        $messages = $recipient->get_messages();
        (new View("messages"))->show(array("recipient" => $recipient, "user" => $user, "messages" => $messages, "errors" => $errors));
    }

    //méthode outil pour poster un  message. renvoie un tableau d'erreurs 
    //eventuellement vide
    private function post($user, $recipient) {
        $errors = [];
        if (isset($_POST['body'])) {
            $body = $_POST['body'];
            $private = isset($_POST['private']) ? TRUE : FALSE;
            $message = new Message($user, $recipient, $body, $private);
            $errors = $message->validate();
            if(empty($errors)){
                $user->write_message($message);                
            }
        }
        return $errors;
        
    }

    //méthode outil pour déterminer le destinataire courant
    private function get_recipient($user) {
        if (!isset($_GET["param1"]) || $_GET["param1"] == "") {
            return $user;
        } else {
            return Member::get_member_by_pseudo($_GET["param1"]);
        }
    }

    //gère la suppression d'un message
    public function delete() {
        $message = $this->remove_message();
        if ($message) {
            $member = $message->recipient;
            $this->redirect("message", "index", $member->pseudo);
        } else {
            throw new Exception("Wrong/missing ID or action no permited");
        }
    }

    //méthode outil pour supprimer un message.
    //indique si le message a été effectivement supprimé (dans ce cas renvoie le message)
    //sinon renvoie false
    private function remove_message() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST['id_message']) && $_POST['id_message'] != "") {
            $post_id = $_POST['id_message'];
            $message = Message::get_message($post_id);
            if ($message) {
                return $message->delete($user);
            } 
        }
        return false;
    }

}
