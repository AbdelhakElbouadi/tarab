<?php 
	include 'includes/header.php'; 
?>

<h1>Great Albums</h1>
<div class="albumsContainer">
	<?php 
	$albums = $cnx->query("SELECT * from albums ORDER BY RAND() LIMIT 10");
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

<?php 	
	include 'includes/footer.php';
?>
