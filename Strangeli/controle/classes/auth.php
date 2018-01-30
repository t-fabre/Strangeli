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
     * Doit etret appelé apres un appel  a restrict
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
     * @param $gender
     * @param $email
     * @param $phone
     * @param $city
     * @param $birth
     * @param $password_user
     * @return mixed l'id de l'utilisateur créé
     */
    public function registerUser($db, $fname, $lname, $birth ,$gender ,$email, $phone, $city, $password_user, $picture, $id_fb){

        $smsCode = Str::randomSMSToken(4);
        $password = $this->hashPass($password_user);
        if($picture == NULL){
          $token = Str::random(60);
          $confirm_date = NULL;
          $profilePic = ($gender == 0)?'avatar_male.png':'avatar_female.png' ;
        }else{
          $profilePic = $picture;
          $token = NULL;
          $datecount = new DateTime();
          $confirm_date = $datecount->format("Y-m-d");
        }


        $db->query('INSERT INTO user SET fname = ?, lname = ?, city_user = ?, mail_user = ?, phone_user = ?, birth = ?, gender = ?, lvl_foot = ?, lvl_tennis = ?, lvl_padel = ?, lvl_squash = ?, password_user = ?, valid_phone = ?, gameplayed = ?, lates = ?, misses = ?, profil_pic = ?, ban_pic = ?, int_foot = ?, int_tennis = ?, int_padel = ?, int_squash = ?, confirmedat_user = NOW(), id_fb = ?', [
            $fname,
            $lname,
            $city,
            $email,
            $phone,
            $birth,
            0,
            1,
            1,
            1,
            1,
            $password,
            $smsCode,
            0,
            0,
            0,
            $profilePic,
            0,
            1,
            1,
            1,
            1,
            $id_fb
        ]);
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
	    $this->deleteCookie($db);
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
