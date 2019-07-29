<?php 
	
	unset($_SESSION['userLoggedIn']);
	unset($_SESSION['userLoggedInId']);	
	session_start();
	session_destroy();
	header("Location: register.php");
?>