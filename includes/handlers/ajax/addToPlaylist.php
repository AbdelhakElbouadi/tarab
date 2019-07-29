<?php 
	
	include '../../dbconfig.php';

	$response;

	if(isset($_POST['playlist']) && isset($_POST['song'])){
		$playlist = $_POST['playlist'];
		$song = $_POST['song'];

		$count = $cnx->query("SELECT count(playlistOrder) + 1 as num FROM playlistSongs WHERE playlist='$playlist'");
		if($cnx->errno){
			die("Error: ".$cnx->error);
		}
		$inc = $count->fetch_array()['num'];
		$result = $cnx->query("INSERT INTO playlistSongs VALUES(0, '$playlist', '$song', '$inc')");
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