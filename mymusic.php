<?php 
include 'includes/header.php'; 
?>


<div class="playlistContainer">
	<div class="playlistHeader">
		<h1>My Favorite Music</h1>
		<button class="button" onclick="createNewPlaylist()">NEW PLAYLIST</button>
	</div>

	<div class="playlistContent">
		<?php 
		$userLoggedInId = $_SESSION['userLoggedInId'];
		$albums = $cnx->query("SELECT * FROM playlists WHERE owner='$userLoggedInId' ORDER BY RAND()");
		while ($row = $albums->fetch_array()) {
			echo "<div class='gridViewItem' role='link' onclick='openPage(\"playlist.php?id=".$row['id']."\")'>
			<div class='gridViewImg'>
			<img src='assets/images/icons/playlist.png' alt='Playlist Image'>
			</div>
			<div class='gridViewInfo'><span>".$row['name']."</span></div>
			</div>";
		}
		?>
		<span class="clearfix"></span>
	</div>
	<div class="clearfix"></div>
</div>

<?php 	
include 'includes/footer.php';
?>