<?php 

	include 'classes/User.php';
	session_start();

	$host = "127.0.0.1";
	$uid = "root";
	$pass = "hmar";
	$db = "tarab";

	$timezone = date_default_timezone_set("Africa/Casablanca");

	$cnx = new mysqli($host, $uid, $pass, $db);
	if($cnx->connect_errno){
		die("Cannnot connect to server: ".$cnx->connect_error);
	}

?>