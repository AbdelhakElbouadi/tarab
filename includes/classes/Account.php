<?php 

class Account{

	private $errorArray;
	private $cnx;

	function __construct($cnx){
		$this->errorArray = array();
		$this->cnx = $cnx;
	}

	public function validateUserData($un, $fn, $ln, $em1, $em2, $pw1, $pw2){
		$this->validateUsername($un);
		$this->validateFirstName($fn);
		$this->validateLastName($ln);
		$this->validateEmail($em1, $em2);
		$this->validatePassword($pw1, $pw2);

		if(empty($this->errorArray)){
			return true;
		}else{
			return false;
		}
	}

	public function validateUserDataUpdate($id, $un, $fn, $ln, $em1, $em2, $pw1, $pw2){
		$this->validateUsernameUpdate($un, $id);
		$this->validateFirstName($fn);
		$this->validateLastName($ln);
		$this->validateEmailUpdate($em1, $em2, $id);
		$this->validatePassword($pw1, $pw2);

		if(empty($this->errorArray)){
			return true;
		}else{
			return false;
		}
	}

	public function login($un, $pw){
		$result = $this->cnx->query("SELECT username FROM users WHERE (username='$un' OR email='$un') AND password='$pw'");
		if($this->cnx->errno){
				/*echo "Error At Mysql: ".$this->cnx->error;
				die("Error At Mysql: ".$this->cnx->error);*/
				//TODO: redirect it to server error
			}
			if($result->num_rows <= 0){
				array_push($this->errorArray, "Username or Password are incorrect");
				return false;
			}

			return true;
		}

		public function register($un, $fn, $ln, $em1, $pw1){
			//Insert into db
			$pwdMd5 = md5($pw1);
			$created = date("Y-m-d");
			$profilePic = "assets/images/profile.png";
			$result = $this->cnx->query("INSERT INTO users VALUES(0, '$un', '$fn', '$ln', '$em1', '$pwdMd5', 
				'$created', '$profilePic')");
			if(!$result){
				//echo $this->cnx->error;
				//Forward this to server error handler
				header("Location: server.php");
			}else{
				return true;
			}
		}

		public function update($id, $un, $fn, $ln, $em1, $pw1){
			//Insert into db
			$pwdMd5 = md5($pw1);

			$result = $this->cnx->query("UPDATE users SET username='$un', fname='$fn', lname='$ln', email='$em1', password='$pwdMd5' WHERE id='$id'");
			if(!$result){
				echo "There is something wrong out there";
				$errorMsg = $this->cnx->error;
				header("Location: server.php?msg='$errorMsg'");
			}else{
				return true;
			}
		}

		public function getUserById($id){
			$user = new User($this->cnx, $id);
			return $user;
		}

		public function getUserByName($name){
			$result = $this->cnx->query("SELECT id FROM users WHERE username='$name'");
			$row = $result->fetch_array();
			$id = $row['id'];
			return $this->getUserById($id);
		}

		public function getUserByEmail($email){
			$result = $this->cnx->query("SELECT id FROM users WHERE email='$email'");
			$row = $result->fetch_array();
			$id = $row['id'];
			return $this->getUserById($id);
		}

		public function getLastInserted(){
			return $this->cnx->insert_id;
		}

		public function getErrorArray(){
			return $this->errorArray;
		}

		private function validateUsername($un){
			if(strlen($un) < 3 || strlen($un) > 30){
				array_push($this->errorArray, Constants::USERNAME_CHARACTER_LEN);
				return;
			}

			$result = $this->cnx->query("SELECT username FROM users WHERE username='$un'");
			if($result->num_rows > 0){
				array_push($this->errorArray, Constants::USERNAME_USED);
				return;
			}
		}

		private function validateUsernameUpdate($un, $id){
			if(strlen($un) < 3 || strlen($un) > 30){
				array_push($this->errorArray, Constants::USERNAME_CHARACTER_LEN);
				return;
			}

			$result = $this->cnx->query("SELECT username FROM users WHERE username='$un' AND id<>'$id'");
			if($result->num_rows > 0){
				array_push($this->errorArray, Constants::USERNAME_USED);
				return;
			}else{
				return;
			}
		}

		private function validateFirstName($fn){
			if(strlen($fn) < 3 || strlen($fn) > 30){
				array_push($this->errorArray, Constants::FIRSTNAME_CHARACTER_LEN);
				return;
			}
		}

		private function validateLastName($ln){
			if(strlen($ln) < 3 || strlen($ln) > 30){
				array_push($this->errorArray, Constants::LASTNAME_CHARACTER_LEN);
				return;
			}
		}

		private function validateEmail($em1, $em2){
			
			if($em1 != $em2){
				array_push($this->errorArray, Constants::EMAIL_CONFIRM_MATCH);
				return;
			}

			if(strlen($em1) < 3 || strlen($em1) > 30){
				array_push($this->errorArray, Constants::EMAIL_CHARACTER_LEN);
				return;
			}

			if(!filter_var($em1, FILTER_VALIDATE_EMAIL)){
				array_push($this->errorArray, Constants::EMAIL_FORM_NOT_VALID);
				return;
			}

			$result = $this->cnx->query("SELECT email FROM users WHERE email='$em1'");
			if($result->num_rows > 0){
				array_push($this->errorArray, Constants::EMAIL_USED);
				return;
			}

		}

		private function validateEmailUpdate($em1, $em2, $id){
			
			if($em1 != $em2){
				array_push($this->errorArray, Constants::EMAIL_CONFIRM_MATCH);
				return;
			}

			if(strlen($em1) < 3 || strlen($em1) > 30){
				array_push($this->errorArray, Constants::EMAIL_CHARACTER_LEN);
				return;
			}

			if(!filter_var($em1, FILTER_VALIDATE_EMAIL)){
				array_push($this->errorArray, Constants::EMAIL_FORM_NOT_VALID);
				return;
			}

			$result = $this->cnx->query("SELECT id FROM users WHERE email='$em1' AND id<>'$id'");
			if($result->num_rows > 0){
				array_push($this->errorArray, Constants::EMAIL_USED);
				return;
			}else{
				return;
			}
		}


		private function validatePassword($pw1, $pw2){

			if($pw1 != $pw2){
				array_push($this->errorArray, Constants::PASS_CONFIRM_MATCH);
				return;
			}

			if(strlen($pw1) < 3 || strlen($pw1) > 30){
				array_push($this->errorArray, Constants::PASS_CHARACTER_LEN);
				return;
			}

			if(preg_match("/[^a-zA-Z0-9]/", $pw1)){
				array_push($this->errorArray, Constants::PASS_FORM_NOT_VALID);
				return;
			}
		}
	}
	?>