<?php 
	
	include '../../dbconfig.php';

	$json;

	if(isset($_POST['id'])){
		$albumId = $_POST['id'];
		$result = $cnx->query("SELECT * from albums WHERE id='$albumId'");
		if($cnx->errno){
			die("Error: ".$cnx->error);

		}
		while($row = $result->fetch_array()){
			$json = json_encode($row);
		}
	}

	echo $json;
?>