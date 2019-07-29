<?php 

include 'includes/header.php';
include 'includes/classes/Playlist.php';

if(isset($_GET['id'])){
	$album = new Album($cnx, $_GET['id']);
}

function formatTime($time){
	$lzero  = "";
	$splitted = substr($time, 3);
	return $splitted;
}

?>

<div class="album">
	<div class="albumHeader">
		<div class="albumImg">
			<img src='<?php echo $album->getArtworkPath(); ?>' alt='<?php echo $album->getTitle(); ?>'>
		</div>
		<div class="albumInfo">
			<span class="albumTitle"><?php echo $album->getTitle(); ?></span>
			<span class="albumArtist"><?php echo $album->getArtist()->getName(); ?></span>
			<span class="numSongs"><?php echo $album->getNumberOfSongs(); ?> Songs</span>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="albumSongs">
		<?php 

		$idArray = $album->getSongsId();
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
	</nav>
</div>

<?php 
include 'includes/footer.php';
?>
