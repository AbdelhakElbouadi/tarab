<?php 

include 'includes/header.php';
include 'includes/classes/Playlist.php';

if(!isset($_SESSION['userLoggedIn'])){
	header("Location: register.php");	
}

if(isset($_GET['id'])){
	$playlistId = $_GET['id'];
	$playlist = new Playlist($cnx, $playlistId);
}

function formatTime($time){
	$lzero  = "";
	$splitted = substr($time, 3);
	return $splitted;
}

?>

<div class="album">
	<div class="albumHeader">
		<div class="albumImg white-br-2x">
			<img src='assets/images/icons/playlist.png' alt='playlist'>
		</div>
		<div class="albumInfo">
			<span class="albumTitle"><?php echo $playlist->getName(); ?></span>
			<span class="albumArtist"><?php echo $playlist->getOwner()->getLname()." ".$playlist->getOwner()->getFname(); ?></span>
			<span class="numSongs"><?php echo $playlist->getNumberOfSongs(); ?> Songs</span>
			<button class="button pinky" onclick="deletePlaylist(<?php echo $playlist->getId(); ?>)">Delete Playlist</button>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="albumSongs">
		<?php 

		$idArray = $playlist->getSongsId();
			//echo "<span id='albumId'>".$album->getId()."</span>";
		$i = 1;
		foreach ($idArray as $id) {
			$song = new Song($cnx, $id);

			echo "<div class='songItem' onmouseenter='mouseEnter(this)' onmouseleave='mouseLeave(this)'>
			<div class='songInfo'>
			<div class='songNumDiv'>
			<span class='songNum'>$i</span>
			<span class='songNumplay hidden' onclick='playThisSong(".$song->getId().")'>
			<img src='assets/images/icons/play_triangle.png' alt='PlaySong'></span>
			</div>
			<div class='songDetails'>
			<span class='title'>".$song->getTitle()."</span>
			<span class='artist'>".$song->getArtist()->getName()."</span>
			</div>
			</div>
			<div class='options'>
			<input class='songId' type='hidden' value='".$song->getId()."'>
			<img class='optionsButton' onclick='showPopupMenu(this)' 
			src='assets/images/icons/dots.png' alt='options' >
			</div>
			<div class='songTime'>
			<span class='duration'>".formatTime($song->getDuration())."</span>
			</div>
			</div>";

			$i++;
		}

		?>
	</div>

	<nav class="popupmenu">
		<input type="hidden" class="songId">
		<?php
		$userId = $_SESSION['userLoggedInId'];
		echo Playlist::getPlaylistsDropdown($cnx, $userId); 
		?> 
		<div class="popitem delete" onclick="removeFromPlaylist(this,<?php echo $playlistId; ?> )">Delete From Playlist</div>
	</nav>
</div>

<?php 
include 'includes/footer.php';
?>
