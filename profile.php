<?php 

	include 'includes/header.php';

	if(!isset($_SESSION['userLoggedIn'])){
		header("Location: register.php");	
	}

	$userId = $_SESSION['userLoggedInId'];

	function formatTime($time){
		$lzero  = "";
		$splitted = substr($time, 3);
		return $splitted;
	}

?>

<div class="profile">
	<h1>Profile Settings</h1>
	<div class="profileContainer">
		<button class="button" onclick="openPage('settings.php?id=<?php echo $userId ?>')">Settings</button><br/>
		<a class="button" href="logout.php" >Log Out</a>	
	</div>
</div>

<?php 
include 'includes/footer.php';
?>
