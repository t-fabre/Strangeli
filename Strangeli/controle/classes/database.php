<?php

class Database{

    private $pdo;

    public function __construct($login, $password, $database_name, $host){
        try{
            $this->pdo = new PDO("mysql:host=$host; dbname=$database_name; charset=utf8", $login, $password);
        }
        catch (PDOException $e) {
			echo 'Une erreur est parvenue: '.$e->getMessage();
			exit();
        }
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * @param $statement
     * @param bool|array $params
     * @return PDOStatement
     */

    public function query($statement, $params = false){
		try{
	        if ($params){

	            $req = $this->pdo->prepare($statement);
	            $req->execute($params);
	        }else{

	            $req = $this->pdo->query($statement);
	        }
		}
        catch (PDOException $e) {
			echo 'Une erreur est parvenue: '.$e->getMessage();
			//TODO : logger l'erreur
            //retourner false ?
			exit();
        }
	        return $req;
    }

    public function lastInsertId(){

        return $this->pdo->lastInsertId();
    }
}
