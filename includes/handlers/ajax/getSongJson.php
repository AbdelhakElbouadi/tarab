<?php 
	
	include '../../dbconfig.php';

	$json;

	if(isset($_POST['id'])){
		$trackId = $_POST['id'];
		$result = $cnx->query("SELECT * from songs WHERE id='$trackId'");
		while($row = $result->fetch_array()){
			$json = json_encode($row);
		}
	}

	echo $json;
?>