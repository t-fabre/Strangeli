<?php

class App{

    static $db = null;

    static function getDatabase(){

        if (!self::$db){
            // login , mdp, database name , host
            self::$db = new Database('', '', '', '');
        }

        return self::$db;
    }

    static function getAuth(){

        return new Auth(Session::getInstance());
    }

    static function redirect($pages){

        header("Location: $pages");
        exit();
    }
}
