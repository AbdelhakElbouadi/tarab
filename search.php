<?php 

include 'includes/header.php';

if(isset($_GET['term'])){
	$term = urldecode($_GET['term']);
}else{
	$term = "";
}

function formatTime($time){
	$lzero  = "";
	$splitted = substr($time, 3);
	return $splitted;
}

?>

<div class="searchbar">	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#term").focus();
		});

		function doSearch(){
			clearTimeout(timer);
			timer = setTimeout(function(){
				var term = $("#term").val();
				var page = "search.php?term=" + term;
				openPage(page);
			}, 2000);
		}
	</script>
	<p>Search for an artist, song, album or a playlist</p>
	<input type="text" id="term" onkeyup="doSearch()" value="<?php echo $term; ?>" 
	placeholder="Search for..." onfocus="this.value=this.value">
</div>

<div class="searchResult">
	<?php if(!empty($term) && strlen($term) >= 3){ ?>
		<div class="artistDiv">
			<h2>ARTISTS</h2>
			<?php 
			$result = Artist::searchForArtist($cnx, $term);
			if($result->num_rows > 0){
				echo "<div class='artistContainer'>";
				while($row = $result->fetch_array()){
					$songsNumber = Artist::getNumberOfSongs($cnx, $row['id']);
					echo "<span class='artistName' onclick='openPage(\"artist.php?id=".$row['id']."\")'  >"
					.$row['name']." (".$songsNumber.")</span>";
				}
				echo "</div>";
			}else{
				echo "<p class='noresult'>There is no artist with such name</p>";
			}
			?>
		</div>

		<div class="songDiv">
			<h2>SONGS</h2>
			<?php 
			$songsId = Song::searchForSong($cnx, $term);
			if(!empty($songsId)){
				$i = 1;
				foreach ($songsId as $id) {
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
			}else{
				echo "<p class='noresult'>There is no song with such title</p>";
			}
			?>
		</div>

		<div class="albumDiv">
			<h2>ALBUMS</h2>
			<?php 
			$albums = Album::searchForAlbum($cnx, $term);
			if($albums->num_rows > 0){
				while ($row = $albums->fetch_array()) {
					echo "<span role='link' onclick='openPage(\"album.php?id=".$row['id']."\")'>
					<div class='gridViewItem'>
					<div class='gridViewImg'>
					<img src='".$row['artworkPath']."' alt='Album ".$row['title']."'>
					</div>
					<div class='gridViewInfo'><span>".$row['title']."</span></div>
					</div></span>";
				}
				echo "<span class='clearfix'></span>";
			}else{
				echo "<p class='noresult'>There is no album with such title</p>";
			}
			?>
		</div>
	<?php } ?>
</div>

<?php 
include 'includes/footer.php';
?>
