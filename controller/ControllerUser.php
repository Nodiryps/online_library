<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser extends Controller {

    
    
    public function index() {
     $user=self::get_user_or_redirect();
     var_dump($user);
    }

}