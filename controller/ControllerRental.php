<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerRental extends Controller {

    public function index() {
        (new View("basket"))->show();
    }
    
    public function returns() {
        (new View("returns"))->show();
    }

}
