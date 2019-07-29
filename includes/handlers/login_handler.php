<?php 
if(isset($_POST['loginButton'])){

	if(isset($_POST['loginUsername'])){
		$username = sanitize($_POST['loginUsername']);
	}

	if(isset($_POST['loginPassword'])){
		$password = sanitize($_POST['loginPassword']);
		$password = md5($password);
	}

	$account = new Account($cnx);
	$result = $account->login($username, $password);
	if(!$result){
		$errorArray = $account->getErrorArray();
	}else{
		if(filter_var($username, FILTER_VALIDATE_EMAIL)){
			$user = $account->getUserByEmail($username);

		}else{
			$user = $account->getUserByName($username);
		}
		$_SESSION['userLoggedIn'] = $user;
		$_SESSION['userLoggedInId'] = $user->getId();
		header("Location: index.php");
	}
}
?>