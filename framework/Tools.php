<?php

require_once 'View.php';

class Tools {

    //nettoie le string donné
    public static function sanitize($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlspecialchars($var);
        return $var;
    }

    //dirige vers la page d'erreur
    public static function abort($err) {
        (new View("error"))->show(array("error" => $err));
        die;
    }

    //renvoie le string donné haché.
    public static function my_hash($password) {
        $prefix_salt = "vJemLnU3";
        $suffix_salt = "QUaLtRs7";
        return md5($prefix_salt . $password . $suffix_salt);
    }

    /**
     * Permet d'encoder une string au format base64url, c'est-à-dire un format base64 dans 
     * lequel les caractères '+' et '/' sont remplacés respectivement par '-' et '_', ce qui
     * permet d'utiliser le résultat dans un URL.
     *
     * @param string $data La string à encoder.
     * @return string La string encodée.
     */
    private static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

   

    /**
     * Permet de décoder une string encodée au format base64url.
     *
     * @param string $data La string à décoder.
     * @return string La string décodée.
     */
    private static function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }

    /**
     * Permet d'encoder une structure de donnée (par exemple un tableau associatif ou un
     * objet) au format base64url.
     *
     * @param mixed $data La structure de données à encoder.
     * @return string La string résultant de l'encodage.
     */
    public static function url_safe_encode($data) {
        return self::base64url_encode(gzcompress(json_encode($data), 9));
    }

 
    /**
     * Permet d'encoder une structure de donnée (par exemple un tableau associatif ou un
     * objet) au format base64url.
     *
     * @param mixed $data La structure de données à encoder.
     * @return string La string résultant de l'encodage.
     */
    public static function url_safe_decode($data) {
        return json_decode(@gzuncompress(self::base64url_decode($data)), true, 512, JSON_OBJECT_AS_ARRAY);
    }

}
