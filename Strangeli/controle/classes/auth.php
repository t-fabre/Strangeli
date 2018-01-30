<?php

class Auth{

    private $options = [
      'restriction_msg' => "Vous ne pouvez accéder à cette page (session terminée)"
    ];
    /**
     * @var Session
     */
    private $session;

    public function __construct($session, $options = []){

        $this->option = array_merge($this->options, $options);
        $this->session = $session;
    }

    public function hashPass($pass){
        return password_hash($pass,PASSWORD_BCRYPT);
    }


    /**
     * @param $db Database la base de données
     */
    public function updateSession($db){
        $id = $this->session->read('auth')->id_user;
        $user = $db -> query('SELECT * FROM user WHERE id_user = ?', [$id])->fetch();
        $this->session->write('auth', $user);
    }


    /**
     * @return L'utilisateur courant dans la session
     */
    public function getCurrentUser(){
        return $this->session->read('auth');
    }


    /**
     * @param $db Database
     * @param $user_id
     * @return mixed
     */
    public function getUser($db, $user_id ){
        return $db->query('SELECT * FROM user WHERE id_user = ?', [$user_id])->fetch();
    }


    /**
     * @param $db
     * @param $fname
     * @param $lname
     * @param $email
     * @param $password_user
     * @return mixed l'id de l'utilisateur créé
     */
    public function registerUser($db, $email, $fname, $lname, $password_user, $pseudo){

        $password = $this->hashPass($password_user);

        $db->query('INSERT INTO  SET ', []);
        return $db->lastInsertId();
    }


    /**
     * @param $user
     * @param $pass
     * @return bool Authentification reussie ou non
     */
    public function login($user, $pass){
        if (password_verify($pass, $user->password_user) && $user->blacklist == 0) {
            $this->connect($user);
            return true;
        }
        return false;
    }


    /**
     * @param $user mixed Connect utilisateur
     */
    public function connect($user){
        $this->session->write('auth', $user);
    }


    /**
     * Deconnect l'utilisateur courant
     */
    public function logout($db){
	    $this->session->logout('auth');
        header("Location: ../index.php");
        exit();
    }


    /**
     * Met a jour le profil d'un utilisateur
     *
     * @param $db
     * @param $user_id
     * @param $columns array les tableaux des colonnes(keys) et des valeurs(values)
     * @return bool
     */
    public function UpdateUserProfile($db, $user_id, $columns ){

        $user = $db->query('SELECT * FROM user WHERE id_user = ?', [$user_id])->fetch();
        if($user){
            $placeholders = "";
            $cols = array_keys($columns);
            $length = count($cols);
            for ($i=0; $i < $length; $i++){
                $placeholders .= $cols[$i]." = ? ";
                if($i < $length - 1){
                    $placeholders .= " , ";
                }
            }
            $values = array_values($columns);
            $values[] = $user_id;
            $db->query('UPDATE user SET '.$placeholders.' WHERE id_user = ?', $values);
            return true;
        }
        return false;
    }

}
