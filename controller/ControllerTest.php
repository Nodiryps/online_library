<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTest extends Controller {

    public function index() {
        
    }

    public function postarray() {
        $txt = array();
        $chk = array();
        if (isset($_POST['txt'])) {
            $txt = $_POST['txt'];
        }
        if (isset($_POST['chk'])) {
            $chk = $_POST['chk'];
        }
        (new View("test_postarray"))->show(array("txt" => $txt, "chk" => $chk));
    }
    
    
    public function testparams() {
        if(isset($_GET["param1"]))
            echo "param1 : " . $_GET["param1"] . "<br>";
        if(isset($_GET["param2"]))
            echo "param2 : " . $_GET["param2"] . "<br>";
        if(isset($_GET["param3"]))
            echo "param3 : " . $_GET["param3"] . "<br>";   
    }

}
