<?php

/**
 * Database
 * 
 * @author  Ruchira Jayasuriya <ruchiraxj@gmail.com>
 * @version 1.0.0
 * 
 */

class Database{

    private $host      = DB_HOST; //host
    private $user      = DB_USER; //username
    private $pass      = DB_PASS; //password
    private $dbname    = DB_NAME; //database name
 
    private $dbh;
    private $error;
 
    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        
        $options = array(
            PDO::ATTR_PERSISTENT    => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		}
        // Catch any errors
        catch(PDOException $e){
            die("Database Connection Failed: ".$this->error = $e->getMessage());
        }
    }
    
    
    
    public function query($query){
        $this->stmt = $this->dbh->prepare($query);
    }
    
    
    
    public function bind($param, $value, $type = null){
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}


	public function execute(){
		return $this->stmt->execute();
	}

	public function resultset(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function rowCount(){
		return $this->stmt->rowCount();
	}

}

?>