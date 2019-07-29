<div class="navbarContainer">
	<nav class="navbarMenu">
		<ul class="menu">
			<li class="item logo">
				<input type="hidden" id="userId">
				<span onclick="openPage('index.php')" role='link'>
					<img src="assets/images/logo.png" alt="logo">
				</span>
			</li>
			<li class="item search">
				<span onclick="openPage('search.php')" role='link'><i class="fa fa-search"></i> Search</span>
			</li>
			<li class="item">
				<span onclick="openPage('mymusic.php')" role='link'><i class="fa fa-music"></i> Playlist</span> 
			</li>
			<li class="item">
				<span onclick="openPage('profile.php')" role='link'>
					<i class="fa fa-user-circle"></i>
					<?php 
						if(isset($_SESSION['userLoggedIn'])){
							$user = $_SESSION['userLoggedIn'];
							echo $user->getUsername();
						}else{
							echo "Profile";
						}
					?>
				</span>
			</li>
		</ul>
	</nav>	
</div>