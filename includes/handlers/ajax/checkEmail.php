<?php 
	
	include '../../dbconfig.php';

	$response;

	if(isset($_POST['email']) && isset($_POST['user'])){
		$email = $_POST['email'];
		$userId= $_POST['user'];
		$result = $cnx->query("SELECT id FROM users WHERE email='$email' AND id<>'$userId'");
		if($result->num_rows > 0){
			$response = true;
		}else{
			$response = false;
		}
	}

	echo $response;
?>