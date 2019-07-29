<?php 
	
	include '../../dbconfig.php';

	$json;

	if(isset($_POST['id'])){
		$artistId = $_POST['id'];
		$songs = $cnx->query("SELECT id FROM songs WHERE artist='$artistId' ORDER BY plays desc LIMIT 10");
		if($cnx->errno){
			die("Error: ".$cnx->error);
		}
		$songsId = array();
		while($row = $songs->fetch_array()){
			array_push($songsId, $row['id']);
		}
		$json = json_encode($songsId);
	}

	echo $json;
?>