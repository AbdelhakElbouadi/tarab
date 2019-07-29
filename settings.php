<?php 
include 'includes/header.php';
include 'includes/handlers/register_handler.php';

$user = $_SESSION["userLoggedIn"];
?>

<script>

	function sanitize(input){
		input = input.replace(/(<([^>]+)>)/ig,"");
		input = input.replace(" ", "");
		return input;
	}

	function checkUsername(ele){
		var username = $(ele).val();
		username = sanitize(username);
		$.post("includes/handlers/ajax/checkUsername.php", {username: username, user: user})
		.done(function(data){
			console.log("Data=>" + data);
			if(data){
				$(".errorMessage.username").text("Username already exist");
				$("#username").focus();
			}else{
				$(".errorMessage.username").text("");
			}
		});
	}

	function checkEmail(ele){
		var email = $(ele).val();
		email = sanitize(email);
		$.post("includes/handlers/ajax/checkEmail.php", {email: email, user: user})
		.done(function(data){
			if(data){
				if($(ele).is("#email1")){
					$(".errorMessage.emailMsg1").text("Email already exist");
					$("#email1").focus();
				}else{
					$(".errorMessage.emailMsg2").text("Email already exist");
					$("#email1").focus();
				}
			}else{
				$(".errorMessage.emailMsg1").text("");
				$(".errorMessage.emailMsg2").text("");
			}
		});
	}
</script>
<div class="updateContainer">
	<form id="updateForm" method="POST" 
	action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	<h2>Update Account</h2>
	<input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
	<p>
		<span class="errorMessage username">
			<?php 
			echo empty($errorArray) ? "" : findErrorMessage("username", $errorArray); 
			?>	
		</span>
		<label for="username">Username</label>
		<input type="text" name="username" id="username" placeholder="e.g:joe_doe" 
		value="<?php echo $user->getUsername(); ?>" onblur="checkUsername(this)" required="true">	
	</p>

	<p>
		<span class="errorMessage fname">
			<?php 
			echo empty($errorArray) ? "" : findErrorMessage("firstName", $errorArray); 
			?>
		</span>
		<label for="firstName">First name</label>
		<input type="text" name="firstName" id="firstName" placeholder="e.g:joe" 
		value="<?php echo $user->getFname();  ?>"  required="true">	
	</p>

	<p>
		<span class="errorMessage lname">
			<?php 
			echo empty($errorArray) ? "" : findErrorMessage("lastName", $errorArray); 
			?>
		</span>
		<label for="lastName">Last name</label>
		<input type="text" name="lastName" id="lastName" placeholder="e.g:doe" 
		value="<?php echo $user->getLname(); ?>"  required="true">	
	</p>

	<p>
		<span class="errorMessage emailMsg1">
			<?php 
			echo empty($errorArray) ? "" : findErrorMessage("email", $errorArray); 
			?>	
		</span>
		<label for="email1">Email</label>
		<input type="text" name="email1" id="email1" 
		placeholder="e.g:joe@doe.com" 
		value="<?php echo $user->getEmail(); ?>" onblur="checkEmail(this)" required="true">	
	</p>

	<p>
		<span class="errorMessage emailMsg2">
			<?php 
			echo empty($errorArray) ? "" : findErrorMessage("email", $errorArray); 
			?>

		</span>
		<label for="email2">Confirm Email</label>
		<input type="text" name="email2" id="email2" 
		placeholder="e.g:joe@doe.com" 
		value="<?php echo $user->getEmail(); ?>" onblur="checkEmail(this)" required="true">	
	</p>

	<p>
		<span class="errorMessage password">
			<?php 
			echo empty($errorArray) ? "" : findErrorMessage("password", $errorArray); 
			?>
		</span>
		<label for="password1">Password</label>
		<input type="password" name="password1" id="password1" required="true">	
	</p>

	<p>
		<span class="errorMessage password">
			<?php 
			echo empty($errorArray) ? "" : findErrorMessage("password", $errorArray); 
			?>
		</span>
		<label for="password2">Confirm Password</label>
		<input type="password" name="password2" id="password2" required="true">	
	</p>

	<input type="submit" name="updateButton" class="button" value="UPDATE" style="margin: 0px auto;">
</form>
</div>
</div>

<?php 
include 'includes/footer.php';
?>