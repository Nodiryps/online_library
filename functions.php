<?php

// nécessaire pour pouvoir utiliser les fonctions sur les dates
date_default_timezone_set("Europe/Brussels");

session_start();

/* ============================== */
/* ===  Fonctions utilitaires === */
/* ============================== */

function sanitize($var)
{
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlspecialchars($var);
    return $var;
}

function my_hash($password)
{
    $prefix_salt = "vJemLnU3";
    $suffix_salt = "QUaLtRs7";
    return md5($prefix_salt.$password.$suffix_salt);        
}

function check_password($password, $hash)
{
    return $hash === my_hash($password);  
}

function log_user($userid, $username, $role='member', $redirect=true){
    $_SESSION["userid"] = $userid;
    $_SESSION["username"] = $username;
    $_SESSION["role"] = $role;
    if ($redirect)
        redirect("profile.php");
}

function abort($err)
{
    global $error;
    $error = $err;
    include 'error.php';
    die;
}

function redirect($url, $statusCode = 303)
{
    header('Location: ' . $url, true, $statusCode);
    die();
}

// Vérifie si tous les champs dont les clés sont passées dans le tableau $fields sont
// présents dans le tableau $arr. Si pas de tableau passé en paramètre, on vérifie dans $_POST.
function check_fields($fields, $arr=null) {
    if ($arr === null)
        $arr = $_POST;
    foreach($fields as $field) {
        if (!isset($arr[$field]))
            return false;
    }
    return true;
}

/* ============================================================ */
/* ===  Fonctions de vérifications de connection et de role === */
/* ============================================================ */

function isLoggedIn() {
    return check_fields(['userid', 'username', 'role'], $_SESSION);
}

function check_login()
{
    global $logged_userid, $logged_username, $logged_role;
    if (!isLoggedIn())
        redirect('index.php');
    else {
        $logged_userid = $_SESSION['userid'];
        $logged_username = $_SESSION['username'];
        $logged_role = $_SESSION['role'];
    }
}

function get_logged_role() {
    return $_SESSION['role'];
}

function get_logged_userid() {
    return $_SESSION['userid'];
}

function get_logged_username() {
    return $_SESSION['username'];
}

function isAdmin() {
    return get_logged_role() === 'admin'; 
}

function isManager() {
    return get_logged_role() === 'manager'; 
}

function isMember() {
    return get_logged_role() === 'member'; 
}

function check_admin() {
    check_login();
    if (!isAdmin())
        abort("You must have the 'admin' role");
}

function check_manager_or_admin() {
    check_login();
    if (!isAdmin() && !isManager())
        abort("You must have the 'manager' or the 'admin' role");
}

/* ======================================= */
/* ===  Fonctions de gestion des dates === */
/* ======================================= */

// Vérifie si une date passée en string au format YYYY-MM-DD est valide
function is_valid_date($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Retourne une date au format YYYY-MM-DD à partir d'une string conforme à celles attendues par strtotime
function get_date($str) {
    $ts = strtotime($str);
    $d = new DateTime();
    $d->setTimestamp($ts);
    return $d->format('Y-m-d');
}

// Formatte une date, donnée dans le format YYYY-MM-DD, au format d'affichage DD/MM/YYYY
function format_date($date) {
    return $date === null ? '' : (new DateTime($date))->format('d/m/Y');
}

/* =============================================== */
/* ===  Fonctions d'accès à la base de données === */
/* =============================================== */

function connect(){
    $dbhost = "localhost";
    $dbname = "prwb_1819_a00";
    $dbuser = "root";
    $dbpassword = "root";

    try
    {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", "$dbuser", "$dbpassword");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    catch (Exception $exc)
    {
        abort("Error while accessing database. Please contact your administrator.");
    }
}

function sql_fetch($sql, $params=[]) {
    $pdo = connect();
    try
    {
        $query = $pdo->prepare($sql);
        $query->execute($params);
        $row = $query->fetch();
    }
    catch (Exception $exc)
    {
        abort("Error while accessing database. Please contact your administrator.<br>{$exc->getmessage()}");
    }
    if($query->rowCount() === 0) {
        return false;
    } else {
        return $row;
    }
}

function sql_fetch_all($sql, $params=[]) {
    $pdo = connect();
    try
    {
        $query = $pdo->prepare($sql);
        $query->execute($params);
        $rows = $query->fetchAll();
    }
    catch (Exception $exc)
    {
        abort("Error while accessing database. Please contact your administrator.<br>{$exc->getmessage()}");
    }
    return $rows;
}

function sql_execute($sql, $params=[], $returnLastInsertId=false) {
    $pdo = connect();
    try
    {
        $query = $pdo->prepare($sql);
        $query->execute($params);
        if ($returnLastInsertId)
            return $pdo->lastInsertId();
    }
    catch (Exception $exc)
    {
        abort("Error while accessing database. Please contact your administrator.<br>{$exc->getmessage()}");
    }
}

function get_user($id) {
    if (!filter_var($id, FILTER_VALIDATE_INT)) return false;
    return sql_fetch("SELECT * FROM user where id = :id", array("id" => $id));
}

function get_user_by_name($username){
    return sql_fetch("SELECT * FROM user where username = :username", array("username" => $username));
}

function get_user_by_email($email){
    return sql_fetch("SELECT * FROM user where email = :email", array("email" => $email));
}

//pre : user does'nt exist yet
function add_user($username, $password, $fullname, $email, $birthdate, $role='member'){
    if (empty($birthdate))
        $birthdate = null;
    $id = sql_execute("INSERT INTO user(username,password, fullname, email, birthdate, role)
                 VALUES(:username,:password, :fullname, :email, :birthdate, :role)",
        array(
            "username"=>$username, 
            "password"=>my_hash($password),
            "fullname"=>$fullname,
            "email"=>$email,
            "birthdate"=>$birthdate,
            "role"=>$role
        ),
        true  // pour récupérer l'id généré par la BD
    );
    return $id;
}

function update_user($id, $username, $fullname, $email, $birthdate, $role) {
    if (empty($birthdate))
        $birthdate = null;
    sql_execute("UPDATE user SET username=:username, fullname=:fullname, email=:email, 
                 birthdate=:birthdate, role=:role WHERE id=:id",
        array(
            "username"=>$username,
            "fullname"=>$fullname,
            "email"=>$email, 
            "birthdate"=>$birthdate, 
            "role"=>$role, 
            "id"=>$id
        )
    );
}

function get_users() {
    return sql_fetch_all("SELECT * from user order by fullname asc");
}

function count_admins() {
    $row = sql_fetch("SELECT count(*) from user where role='admin'");
    return (int)$row[0];
}

function delete_user($id) {
    if (!filter_var($id, FILTER_VALIDATE_INT)) return false;
    sql_execute('DELETE FROM user WHERE id=:id', array('id' => $id));
}

function check_string_length($str, $min, $max) {
    $len = strlen(trim($str));
    return $len >= $min && $len <= $max;
}

function validate_user($id, $username, $password, $password_confirm, $fullname, $email, $birthdate) {
    $errors = [];
    $member = get_user_by_name($username);
    if($member && $member['id'] !== $id)
        $errors[] = "This user name is already used.";
    if(empty($username)){
        $errors[] = "User Name is required.";
    } 
    elseif(!check_string_length($username, 3, 32)) {
        $errors[] = "User Name length must be between 3 and 32 characters.";
    } 
    if(empty($password)){
        $errors[] = "Password is required.";
    } 
    if(empty($fullname)){
        $errors[] = "Full Name is required.";
    } 
    elseif(!check_string_length($fullname, 3, 255)) {
        $errors[] = "Full Name length must be between 3 and 255 characters.";
    } 
    if(empty($email)){
        $errors[] = "Email is required.";
    } 
    elseif(!check_string_length($email, 5, 64)) {
        $errors[] = "Email length must be between 5 and 64 characters.";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email address is not valid";
    }
    else {
        $member = get_user_by_email($email);
        if($member && ($id === null || $member['id'] !== $id))
            $errors[] = "This email address is already used";
    }
    if($password != $password_confirm) {
        $errors[] = "You have to enter twice the same password.";
    }
    if (!empty($birthdate)) {
        if (!is_valid_date($birthdate)) {
            $errors[] = "Birth Date is not valid";
        }
        if ($birthdate > get_date('-18 years')) {
            $errors[] = "User must be at least 18 years old";
        }
    }
    return $errors;
}

?>