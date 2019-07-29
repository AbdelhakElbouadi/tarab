<?php 

include_once 'includes/Constants.php';
include_once 'includes/classes/Account.php';

function sanitize($input){
	$input = strip_tags($input);
	$input = str_replace(" ", "", $input);
	return $input;
}

function findErrorMessage($name, $ar){
	foreach ($ar as $value) {
		if(stristr($value, $name)){
			return $value;
		}
	}

	return "";
}

function getValueInput($name){
	if(isset($_POST[$name])){
		echo $_POST[$name];
	}
}

if(isset($_POST['registerButton'])){

	$username = $firstName = $lastName = $email1 = $email2 = $password1 = $password2 = "";

	if(isset($_POST['username'])){
		$username = sanitize($_POST['username']);
	}

	if(isset($_POST['firstName'])){
		$firstName = sanitize($_POST['firstName']);
	}

	if(isset($_POST['lastName'])){
		$lastName = sanitize($_POST['lastName']);
	}

	if(isset($_POST['email1'])){
		$email1 = sanitize($_POST['email1']);
	}

	if(isset($_POST['email2'])){
		$email2 = sanitize($_POST['email2']);
	}

	if(isset($_POST['password1'])){
		$password1 = sanitize($_POST['password1']);
	}

	if(isset($_POST['password2'])){
		$password2 = sanitize($_POST['password2']);
	}

	$account = new Account($cnx);
	$wasSuccessful = $account->validateUserData($username, $firstName, 
		$lastName, $email1, $email2, $password1, $password2);
	if($wasSuccessful){
		$result = $account->register($username, $firstName, $lastName, $email1, $password1);
		if($result){
			$id = $account->getLastInserted();
			$user = $account->getUserById($id);
			$_SESSION['userLoggedIn'] = $user;
			$_SESSION['userLoggedInId'] = $id;
			header("Location: index.php");
		}
	}else{
		$errorArray = $account->getErrorArray();
	}
}

if(isset($_POST['updateButton'])){

	$id = $username = $firstName = $lastName = $email1 = $email2 = $password1 = $password2 = "";

	if(isset($_POST['id'])){
		$id = $_POST['id'];
	}

	if(isset($_POST['username'])){
		$username = sanitize($_POST['username']);
	}

	if(isset($_POST['firstName'])){
		$firstName = sanitize($_POST['firstName']);
	}

	if(isset($_POST['lastName'])){
		$lastName = sanitize($_POST['lastName']);
	}

	if(isset($_POST['email1'])){
		$email1 = sanitize($_POST['email1']);
	}

	if(isset($_POST['email2'])){
		$email2 = sanitize($_POST['email2']);
	}

	if(isset($_POST['password1'])){
		$password1 = sanitize($_POST['password1']);
	}

	if(isset($_POST['password2'])){
		$password2 = sanitize($_POST['password2']);
	}

	$account = new Account($cnx);
	$wasSuccessful = $account->validateUserDataUpdate($id, $username, $firstName, 
		$lastName, $email1, $email2, $password1, $password2);
	if($wasSuccessful){
		$result = $account->update($id, $username, $firstName, $lastName, $email1, $password1);
		if($result){
			$user = $account->getUserByName($username);
			$_SESSION['userLoggedIn'] = $user;
			header("Location: profile.php");
		}
	}else{
		$errorArray = $account->getErrorArray();
	}
}

?>