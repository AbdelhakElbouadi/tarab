<?php 
	
	include '../../dbconfig.php';

	$response;

	if(isset($_POST['name']) && isset($_POST['owner'])){
		$name = $_POST['name'];
		$owner = $_POST['owner'];
		$dateCreated = date('Y-m-d');
		$result = $cnx->query("INSERT INTO playlists VALUES(0, '$name', '$owner', '$dateCreated')");
		if($cnx->errno){
			die("Error: ".$cnx->error);
		}
		if($result){
			$hack = $cnx->query("SELECT id FROM playlists ORDER BY id DESC LIMIT 1");
			$ho = $hack->fetch_array();
			$response = $ho['id'];
		}else{
			$response = $result;
		}
	}

	echo $response;
?>