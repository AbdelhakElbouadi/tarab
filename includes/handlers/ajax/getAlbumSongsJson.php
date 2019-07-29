<?php 
	
	include '../../dbconfig.php';

	$json;

	if(isset($_POST['id'])){
		$albumId = $_POST['id'];
		$songs = [];
		$result = $cnx->query("SELECT id from songs WHERE album='$albumId'");
		while($row = $result->fetch_assoc()){
			array_push($songs, $row['id']);
		}
		$json = json_encode($songs);
	}

	echo $json;
?>