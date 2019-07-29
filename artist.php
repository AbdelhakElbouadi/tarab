<?php 

include 'includes/header.php';
include_once 'includes/classes/Artist.php';

if(isset($_GET['id'])){
	$artist = new Artist($cnx, $_GET['id']);
	$artistId = $artist->getId();
}

function formatTime($time){
	$lzero  = "";
	$splitted = substr($time, 3);
	return $splitted;
}
?>

<div class="artistContainer">
	<div class="artistInfo">
		<h1><?php echo $artist->getName(); ?></h1>
		<button class="button" onclick="playThisList(<?php echo $artistId; ?>)">PLAY</button>
	</div>
	<div class="artistSongs">
		<?php 
		$songs = $cnx->query("SELECT * FROM songs WHERE artist='$artistId' ORDER BY plays desc LIMIT 10");
		$i = 1;
		while($row = $songs->fetch_array()){
			echo "<div class='songItem' onmouseenter='mouseEnter(this)' onmouseleave='mouseLeave(this)'>
			<div class='songInfo'>
			<div class='songNumDiv'>
			<span class='songNum'>$i</span>
			<span class='songNumplay hidden' onclick='playThisSong(".$row['id'].")'>
			<img src='assets/images/icons/play_triangle.png' alt='PlaySong'></span>
			</div>
			<div class='songDetails'>
			<span class='title'>".$row['title']."</span>
			<span class='artist'>".$artist->getName()."</span>
			</div>
			</div>
			<div class='options'>
			<input class='songId' type='hidden' value='".$row['id']."'>
			<img class='optionsButton' onclick='showPopupMenu(this)' 
			src='assets/images/icons/dots.png' alt='options' >
			</div>
			<div class='songTime'>
			<span class='duration'>".formatTime($row['duration'])."</span>
			</div>
			</div>";
			$i++;
		}

		?>
	</div>
	<nav class="popupmenu">
		<input type="hidden" class="songId">
		<?php
			$userLoggedInId = $_SESSION['userLoggedInId'];
			echo Playlist::getPlaylistsDropdown($cnx, $userLoggedInId); 
		?> 
	</nav>
	<div class="artistAlbums">
		<?php 
		//We choose the more relevant albums the most visited one by fans.
		$albums = $cnx->query("SELECT * from albums WHERE artist='$artistId' ORDER BY RAND() LIMIT 10");
		while ($row = $albums->fetch_array()) {
			echo "<span role='link' onclick='openPage(\"album.php?id=".$row['id']."\")'><div class='gridViewItem'>
			<div class='gridViewImg'>
			<img src='".$row['artworkPath']."' alt='Album ".$row['title']."'>
			</div>
			<div class='gridViewInfo'><span>".$row['title']."</span></div>
			</div></span>";
		}
		?>	
	</div>
</div>


<?php 
	include 'includes/footer.php';
?>
