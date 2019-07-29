<?php 
include "includes/dbconfig.php";
include_once "includes/Constants.php";
include_once "includes/classes/Account.php";
include "includes/handlers/register_handler.php";
include "includes/handlers/login_handler.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Tarab</title>
	<link rel="stylesheet" type="text/css" href="assets/style/register.css">
	<script type="text/javascript" src="assets/script/jquery.min.js"></script>
</head>
<body>
	<div id="background">
		<div class="inputContainer">
			<div class="loginContainer">
				<form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
					<h2>Login In</h2>	
					<p>
						<span class="errorMessage">
							<?php echo empty($errorArray) ? "" : findErrorMessage("username", $errorArray); ?>
							</span>
							<label for="loginUsername">Username</label>
							<input type="text" name="loginUsername" id="loginUsername" placeholder="e.g:joe doe or email"
							value="<?php getValueInput('loginUsername'); ?>" required="true">
						</p>

						<p>
							<span class="errorMessage">
								<?php echo empty($errorArray) ? "" : findErrorMessage("password", $errorArray); ?></span>
								<label for="loginPassword">Password</label>
								<input type="password" name="loginPassword" id="loginPassword" required="true">	
						</p>

							<input type="submit" name="loginButton" class="button" value="LOGIN IN">
						<p id="loginToggle">You don't have an account? click here</p>
						</form>
			</div>
			

			<div class="registerContainer">
						<form id="registerForm" method="POST" 
						action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
						<h2>Create New Account</h2>
						<p>
							<span class="errorMessage">
								<?php 
								echo empty($errorArray) ? "" : findErrorMessage("username", $errorArray); 
								?>	
							</span>
							<label for="username">Username</label>
							<input type="text" name="username" id="username" placeholder="e.g:joe_doe" 
							value="<?php getValueInput('username'); ?>" required="true">	
						</p>

						<p>
							<span class="errorMessage">
								<?php 
								echo empty($errorArray) ? "" : findErrorMessage("firstName", $errorArray); 
								?>
							</span>
							<label for="firstName">First name</label>
							<input type="text" name="firstName" id="firstName" placeholder="e.g:joe" 
							 value="<?php getValueInput('firstName'); ?>"  required="true">	
						</p>

						<p>
							<span class="errorMessage">
								<?php 
								echo empty($errorArray) ? "" : findErrorMessage("lastName", $errorArray); 
								?>
							</span>
							<label for="lastName">Last name</label>
							<input type="text" name="lastName" id="lastName" placeholder="e.g:doe" 
							value="<?php getValueInput('lastName'); ?>"  required="true">	
						</p>

						<p>
							<span class="errorMessage">
								<?php 
								echo empty($errorArray) ? "" : findErrorMessage("email", $errorArray); 
								?>	
							</span>
							<label for="email1">Email</label>
							<input type="email" name="email1" id="email1" 
							placeholder="e.g:joe@doe.com" 
							value="<?php getValueInput('email1'); ?>"  required="true">	
						</p>

						<p>
							<span class="errorMessage">
								<?php 
								echo empty($errorArray) ? "" : findErrorMessage("email", $errorArray); 
								?>

							</span>
							<label for="email2">Confirm Email</label>
							<input type="email" name="email2" id="email2" 
							placeholder="e.g:joe@doe.com" 
							value="<?php getValueInput('email2'); ?>"  required="true">	
						</p>

						<p>
							<span class="errorMessage">
								<?php 
								echo empty($errorArray) ? "" : findErrorMessage("password", $errorArray); 
								?>
							</span>
							<label for="password1">Password</label>
							<input type="password" name="password1" id="password1" required="true">	
						</p>

						<p>
							<span class="errorMessage">
								<?php 
								echo empty($errorArray) ? "" : findErrorMessage("password", $errorArray); 
								?>
							</span>
							<label for="password2">Confirm Password</label>
							<input type="password" name="password2" id="password2" required="true">	
						</p>

						<input type="submit" name="registerButton" class="button" value="SIGN UP">
						<p id="registerToggle">Already have an account login from here</p>
				</form>
			</div><!--registerContainer-->
		</div><!--inputContainer-->
		
		<div class="textContainer">
			<h1>Listen To Great Music Here</h1>

			<h2>There is no need for credit card anymore</h2>
			<ul>
				<li>Discover greatest hits</li>
				<li>Create your own playlist</li>
				<li>Hooray for music</li>
			</ul>
		</div><!--textContainer-->
	</div><!--background-->
	<script type="text/javascript">
		$(document).ready(function(){

			//On
			$(".loginContainer").show();
			$(".registerContainer").hide();

			$("#loginToggle").click(function(){
				$(".loginContainer").hide();
				$(".registerContainer").show();
			});

			$("#registerToggle").click(function(){
				$(".loginContainer").show();
				$(".registerContainer").hide();
			});
		});
	</script>
</body>
</html>
