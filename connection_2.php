<?php
	class Database{
		private $dsn = "mysql:host=localhost;dbname=egm_receipts_book";
		private $username = "root";
		private $password = "";

		public $conn;

		public function __construct(){
			try{
				$this->conn = new PDO($this->dsn, $this->username, $this->password);

				// echo 'Conneted Successfully to the database!';

			}catch (PDOException $e){
				echo 'Error : '.$e->getMessage();
			}
			return $this->conn;
		}
	}

	// $object = new Database();
?>