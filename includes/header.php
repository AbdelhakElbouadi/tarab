<?php 
	include 'includes/includedFiles.php';

	if(!isset($_SESSION['userLoggedIn'])){
		header("Location: register.php");	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome To Music</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="assets/style/style.css">
	<link rel="stylesheet" type="text/css" href="assets/style/fontawesome/css/all.css">
	<link rel="stylesheet" type="text/css" href="assets/style/bootstrap.min.css">
	<script type="text/javascript" src="assets/script/jquery.min.js"></script>
	<script type="text/javascript" src="assets/style/fontawesome/js/all.js"></script>
	<script type="text/javascript" src="assets/script/bootstrap.min.js"></script>
	<script type="text/javascript">
		var user;
		$(document).ready(function(){
			//Global Variable
			$("#userId").val(<?php echo $_SESSION['userLoggedInId']; ?>);
			user = $("#userId").val();

			$(".controlButton.pause").hide();

			$(".controlButton.play").click(function(){
				$(".controlButton.pause").show();
				$(".controlButton.play").hide();
			});

			$(".controlButton.pause").click(function(){
				$(".controlButton.pause").hide();
				$(".controlButton.play").show();
			});

    		window.onpopstate = history.onpushstate = function(e) {
    			var path = window.location.href;
    			var currentPage = path.substring(path.lastIndexOf('/') + 1);
    			openPage(currentPage);	
    		};
		});

		function mouseEnter(e){
			$(e).find("div.songNumDiv").each(function(i){
				var children = $(this).children();
				$(children[0]).hide();
				$(children[1]).show();
				return false;
			});
		}

		function mouseLeave(e){
			$(e).find("div.songNumDiv").each(function(i){
				var children = $(this).children();
				$(children[0]).show();
				$(children[1]).hide();
				return false;
			});
		}
	</script>
</head>
<body>
<div class="wrapper">
<div class="topContainer">
<?php include 'includes/navbar.php'; ?>
<div class="mainContainer">
<div id="mainWrapper">
