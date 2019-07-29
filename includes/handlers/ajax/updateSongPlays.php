<?php 
	
	include '../../dbconfig.php';

	$json;

	if(isset($_POST['id']) && isset($_POST['plays'])){
		$songId = $_POST['id'];
		$plays = $_POST['plays'] + 1;
		$result = $cnx->query("UPDATE songs SET plays='$plays' WHERE id='$songId'");
		if($cnx->errno){
			die("Error: ".$cnx->error);
		}
		$json = json_encode($result);
	}

	echo $json;
?>