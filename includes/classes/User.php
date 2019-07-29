<?php 

	class User{

		private $cnx;
		private $id; 
		private $username;
		private $email;
		private $fname;
		private $lname;
		private $pwd;
		private $created;

		function __construct($cnx, $id){
			$this->cnx = $cnx;
			$this->id = $id;
			$result = $this->cnx->query("SELECT * FROM users WHERE id='$this->id'");
			$row = $result->fetch_array();
			$this->username = $row['username'];
			$this->email = $row['email'];
			$this->pwd = $row['password'];
			$this->lname = $row['lname'];
			$this->fname = $row['fname'];
			$this->created = $row['created'];
		}

		public function getId(){
			return $this->id;
		}

		public function getUsername(){
			return $this->username;
		}

		public function getEmail(){
			return $this->email;
		}

		public function getLname(){
			return $this->lname;
		}

		public function getFname(){
			return $this->fname;
		}

		public function getPassword(){
			return $this->pwd;
		}

		public function getCreationDate(){
			return $this->created;
		}
	}

?>