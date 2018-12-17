<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Spy
 */
require_once 'framework/model';

class User {
    var $id;
    var $username;
    var $password;
    var $fullname;
    var $email;
    var $birthdate;
    var $role;
    
    public function __construct($username, $password, $fullname, $email, $birthdate, $role) {
        $this->username = $username;
        $this->password = $password;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->role = $role;
    }
    
    
    
    function get_user($id) {
        return sql_fetch("SELECT * FROM user where id = :id", array("id" => $id));
}
}
