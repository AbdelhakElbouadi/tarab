<?php 
	
	include '../../dbconfig.php';

	$response;

	if(isset($_POST['playlist']) && isset($_POST['song'])){
		$playlist = $_POST['playlist'];
		$song = $_POST['song'];

		$result = $cnx->query("DELETE FROM playlistSongs WHERE playlist='$playlist' AND song='$song'");
		if($cnx->errno){
			die("Error: ".$cnx->error);
		}
		if($result){
			$response = true;
		}else{
			$response = false;
		}
	}

	echo $response;
?>