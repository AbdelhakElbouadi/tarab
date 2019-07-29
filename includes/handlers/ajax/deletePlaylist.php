<?php 
	
	include '../../dbconfig.php';

	$response;

	if(isset($_POST['id'])){
		$id = $_POST['id'];

		$result = $cnx->query("DELETE FROM playlists WHERE id='$id'");
		if($cnx->errno){
			die("Error: ".$cnx->error);
		}
		if($result){
			$response = "success";
		}else{
			$response = "failure";
		}
	}

	echo $response;
?>