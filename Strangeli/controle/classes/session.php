<?php

class Session{

    static $instance;

    /**
     * @return mixed
     */
    public static function getInstance(){

        if (!self::$instance){

            self::$instance = new Session();
        }

        return self::$instance;
    }

    public function __construct(){
		    ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 365);
		    ini_set('session.cookie-lifetime', 60 * 60 * 24 * 365);
        session_start();
    }

    public function write($key, $value){

        $_SESSION[$key] = $value;
    }

    public function read($key){

        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function logout($key){

        unset($_SESSION[$key]);
    }

    public function sessionKeyExists($key){
        return $this->read($key) != null;
    }
}
