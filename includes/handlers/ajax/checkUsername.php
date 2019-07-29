<?php 
	
	include '../../dbconfig.php';

	$response;

	if(isset($_POST['username']) && isset($_POST['user'])){
		$username = $_POST['username'];
		$userId = $_POST['user'];
		$result = $cnx->query("SELECT id FROM users WHERE username='$username' AND id<>'$userId'");
		if($result->num_rows > 0){
			$response = true;
		}else{
			$response = false;
		}
	}

	echo $response;
?>