<?php 
	
	include '../../dbconfig.php';

	$json;

	if(isset($_POST['id'])){
		$artistId = $_POST['id'];
		$result = $cnx->query("SELECT * from artists WHERE id='$artistId'");
		if($cnx->errno){
			die("Error: ".$cnx->error);
		}
		while($row = $result->fetch_array()){
			$json = json_encode($row);
		}
	}

	echo $json;
?>