<?php 
$result = $cnx->query("SELECT id FROM songs ORDER BY RAND() LIMIT 10");
$songsIdArray = array();
while ($row = $result->fetch_array()) {
	array_push($songsIdArray, $row['id']);
}
$jsonSongsArray = json_encode($songsIdArray);
?>

<link rel="stylesheet" type="text/css" href="assets/style/ion.rangeSlider.css">
<script type="text/javascript" src="assets/script/script.js"></script>
<script type="text/javascript" src="assets/script/ion.rangeSlider.min.js"></script>
<script type="text/javascript">	
	$(document).ready(function(){
		audio = document.createElement("audio");
		player = new Player(audio);
		currentPlayingList = <?php echo $jsonSongsArray; ?>; 
		setTrack(currentPlayingList[0], currentPlayingList, false);
		console.log('Current Playing List Hola ' + currentPlayingList);

		//Deal with volume and track playing
		/*$(".nowPlayingBar").on('mousedown mousemove touchdown touchmove', function(e){
			var current = $(this);
			e.preventDefault();
			if(current.hasClass('slider') || current.hasClass('slidercontainer')){
				console.log('This is a slider');
			}else{
				e.preventDefault();
			}
		});*/

		$(audio).on("canplay", function(){
			$(".processTime.current").text("0:00");
			$(".processTime.remaining").text(formatTime(player.duration));
			volumeInitialize();
			//console.log("The Duration In GNU=>" + player.duration);
			//Update for the second slider
			if(!updatingSlide){
				let data = trackSlider.data("ionRangeSlider");
				data.update({
					max: player.duration
				});	
			}
		});

		$(audio).on('timeupdate', function(){
			$(".processTime.current").text(formatTime(player.currentTime));
			$(".processTime.remaining").text(formatTime(player.remainingTime));
			var actual = Math.round(player.currentTime*100/player.duration);
			$(".middle .progress").css({"width": actual+"%"});
			//Update for the second slider
			if(!updatingSlide){
				let data = trackSlider.data("ionRangeSlider");
				data.update({
					from: player.currentTime
				});
			}
		});

		$(audio).on('ended', function(){
			$(".controlButton.play").show();
			$(".controlButton.pause").hide();
			nextSong();
		});

		$(".volumeToggle.volume").show();
		$(".volumeToggle.mute").hide();

		$(audio).on('volumechange', function(){
			if(player.isMuted()){
			}
		});

		$(".right .volumeToggle").on('click', function(e){
			if(!player.isMuted()){
				volume = player.getVolume();
				player.setVolume(0.0);
				player.setMuted(true);
				$(".volumeToggle.volume").hide();
				$(".volumeToggle.mute").show();
				volumeInitialize();
			}else{
				player.setVolume(volume);
				player.setMuted(false);
				$(".volumeToggle.volume").show();
				$(".volumeToggle.mute").hide();
				volumeInitialize();
			}
		});

		
		var mouseDown = false;
		$(".middle .progressSlider.active").on("mouseenter", function(e){
			//A global variable defined in another script
			updatingSlide = true;
		});

		$(".middle .progressSlider.active").on("mouseleave", function(e){
			updatingSlide = false;
		});

		$(".middle .progressSlider.active").on("mousedown", function(e){
			mouseDown = true;
		});

		$(".middle").on("mouseenter", function(e){
			$(".middle .progressBar").hide();
			$(".middle .progressSlider.active").show();
		});

		$(".middle").on("mouseleave", function(e){
			$(".middle .progressBar").show();
			$(".middle .progressSlider.active").hide();
		});

		$(document).on("mouseup", function(e){
			mouseDown = false;
		});

/*		$(".middle .progressSlider.active").on("mousemove", function(e){
		});

		$(".middle .progressSlider.active").on("mouseup", function(e){
		});*/

		//ProgressBarBk
		/*$(".right .progressBarBk").on("mousemove", function(e){
			if(mouseDown){
				volumeOffset(e, this);
			}
		});

		$(".right .progressBarBk").on("mousedown", function(e){
			mouseDown = true;
		});

		$(".right .progressBarBk").on("mouseup", function(e){
			volumeOffset(e, this);	
		});*/

		


		volumeSlider = $("#volumeRange");
		volumeSlider.ionRangeSlider({
			skin: "big",
			min: 0,
			max: 100,
			hide_min_max: true,
			from: player.getVolume()*100,
			step: 1,
			onStart: function (data) {
			},
			onChange: function (data) {
				var value = data.input.val();
				player.setVolume(value/100);
			},
			onFinish: function (data) {
			},
			onUpdate: function (data) {
			}
		});

		console.log("The Provided Duration =>" + player.duration);

		trackSlider = $("#trackRange");
		trackSlider.ionRangeSlider({
			skin: "big",
			min: 0,
			hide_min_max: true,
			hide_from_to: true,
			step: 1,
			onStart: function (data) {
				//console.log("Data Duration=>" + data.max);
			},
			onChange: function (data) {
				//If there is no track playing
				/*player is a class that handle the audio player operations like play, pause, stop, playing, notPlaying, changeRecord, etc...just an interface for the html audio element
				*/
				if(!player.notPlaying()){
					player.setCurrentTime(data.from);
					var width = $(".irs-bar.irs-bar--single").width();
					$(".middle .progress").css({"width": width + "px"});	
				}else{
					//data.reset();
				}
			},
			onFinish: function (data) {
				console.log("From OnFinish");
			},
			onUpdate: function (data) {			
			}	
		});

	});

	//Slider must work
	function playThisList(artist){
		$.post("includes/handlers/ajax/getArtistSongsJson.php", {id: artist})
		.done(function(data){
			var songsArray = JSON.parse(data);
			currentPlayingList = songsArray;
			setTrack(currentPlayingList[0], currentPlayingList, true);
		});
	}

	function playThisSong(songId){
		setTrack(songId, null, true);
	}

	function timeOffset(ev, progress){
		var offset = $(progress).offset();
		var width = $(progress).width();
		var x = ev.pageX;
		var duration = player.duration;

		var start = offset.left;
		var end = start + width;
		var scaledWidth = x - start;
		var scaledTime = scaledWidth*duration/width;

		player.setCurrentTime(scaledTime);
		$(".middle .progress").css({"width": scaledWidth});	
	}

	function volumeInitialize(){
		var percentage = player.getVolume();
		var dataIn = volumeSlider.data("ionRangeSlider");
		if(dataIn === undefined){
			console.log("The Value is undefined");
		}else{
			dataIn.update({
				from: percentage*100
			});
		}
	}

	function volumeOffset(ev, progress){
		var width = $(progress).width();
		var xOffset = ev.offsetX;

		var percentage = xOffset/width;
		$(".right .progress").css({"width" : xOffset});
		$(".right .progress").attr('title', Math.round(percentage*100) + "%");
		$(".volumeText").text(Math.round(percentage*100) + "%");
		player.setVolume(percentage);
	}

	function setTrack(trackId, newPlayingList, play){
		var songObject;
		var albumId;
		var artistId;

		$.post("includes/handlers/ajax/getSongJson.php", {id: trackId},
			function(xhr, status, error){
				/*console.log('XHR=>' + JSON.stringify(xhr));
				console.log('Status=>' + status);
				console.log('Error==>' + error);*/
			})
		.done(function(data){
			songObject = JSON.parse(data);
			albumId = songObject.album;
			artistId = songObject.artist; 
			var plays = songObject.plays;

			$.post("includes/handlers/ajax/getAlbumJson.php", {id: albumId})
			.done(function(data){
				var albumObject = JSON.parse(data);
				var albumPage = "openPage('album.php?id=" + albumObject.id + "')";
				$(".left .albumImg img").attr("src", albumObject.artworkPath);
				$(".left .albumInfo .albumName").text(albumObject.title);
				$(".left .albumImg img").attr("onclick", albumPage);
				$(".left .albumInfo .albumName").attr("onclick", albumPage);
			});

			$.post("includes/handlers/ajax/getArtistJson.php", {id: artistId})
			.done(function(data){
				var artistObject = JSON.parse(data);
				var artistPage = "openPage('artist.php?id=" + artistObject.id + "')";
				$(".left .albumInfo .artistName").text(artistObject.name);
				$(".left .albumInfo .artistName").attr("onclick", artistPage);
			});

			player.setTrack(songObject.path);
			updateSongPlays(trackId, plays);

			$("#songId").val(trackId);

			if(newPlayingList == null){
				$.post("includes/handlers/ajax/getAlbumSongsJson.php", {id: albumId})
				.done(function(data){
					albumSongs = JSON.parse(data);
					currentPlayingList = albumSongs;
				});
			}
		});

		if(play){
			$(audio).on("canplay", function(){
				playSong();	
			});
		}
	}

	function updateSongPlays(id, plays){
		$.post("includes/handlers/ajax/updateSongPlays.php", {id: id, plays: plays})
		.done(function(data){

		});		
	}

	function shuffleSong(toshuffle){
		shuffle = toshuffle;
		if(shuffle){
			$(".controlButton.shuffle").hide();
			$(".controlButton.shuffle.active").show();
			oldPlayingList = currentPlayingList.slice();
			shuffleArray(currentPlayingList);
			setTrack(currentPlayingList[0], currentPlayingList, true);
		}else{
			$(".controlButton.shuffle").show();
			$(".controlButton.shuffle.active").hide();
			currentPlayingList = oldPlayingList;
			setTrack(currentPlayingList[0], currentPlayingList, true);
		}
	}

	function repeatSong(torepeat){
		repeat = torepeat;
		if(repeat){
			$(".controlButton.repeat").hide();
			$(".controlButton.repeat.active").show();
		}else{
			$(".controlButton.repeat").show();
			$(".controlButton.repeat.active").hide();
		}
	}

	function nextSong(){
		if(!repeat){
			var acutalSongId = $("#songId").val();
			var index = currentPlayingList.indexOf(acutalSongId);
			if(index == currentPlayingList.length - 1){
				index = 0;
			}else{
				index++;
			}
			setTrack(currentPlayingList[index], currentPlayingList, true);	
		}else{
			player.load();
			playSong();		
		}

	}

	function prevSong(){
		if(!repeat){
			var acutalSongId = $("#songId").val();
			var index = currentPlayingList.indexOf(acutalSongId);
			if(index == 0){
				index = currentPlayingList.length - 1;
			}else{
				index--;
			}
			setTrack(currentPlayingList[index], currentPlayingList, true);
		}else{
			player.load();
			playSong();	
		}
	}

	function playSong(){
		player.play();
		$(".controlButton.play").hide();
		$(".controlButton.pause").show();
	}

	function pauseSong(){
		player.pause();
		$(".controlButton.pause").hide();
		$(".controlButton.play").show();
	}

	function shuffleArray(a) {
		var j, x, i;
		for (i = a.length - 1; i > 0; i--) {
			j = Math.floor(Math.random() * (i + 1));
			x = a[i];
			a[i] = a[j];
			a[j] = x;
		}
		return a;
	}	
</script>

<!-- <div class="nowPlayingBar">
	<div class="left">
		<div class="albumImg">
			<img src="assets/images/square.png" alt="albumImage">
		</div>
		<div class="albumInfo">
			<span class="albumName"></span>
			<span role='link' tabindex="-1" class="artistName"></span>
		</div>

	</div>
	<div class="middle">
		<div class="playerControls content">
			<div class="buttons">
				<input id="songId" type="hidden"  />
				<button class="controlButton shuffle" title="Shuffle Button" onclick="shuffleSong(true)">
					<img src="assets/images/icons/shuffle.png" alt="shuffle">
				</button>

				<button class="controlButton shuffle active hidden" title="Shuffle Button" onclick="shuffleSong(false)">
					<img src="assets/images/icons/shuffle-active.png" alt="shuffle">
				</button>

				<button class="controlButton prev" title="Previous Button" onclick="prevSong()">
					<img src="assets/images/icons/prev.png" alt="prev">
				</button>

				<button class="controlButton play" title="Play Button" onclick="playSong()">
					<img src="assets/images/icons/play.png" alt="play">
				</button>

				<button class="controlButton pause" title="Pause Button" onclick="pauseSong()">
					<img src="assets/images/icons/pause.png" alt="pause">
				</button>

				<button class="controlButton next" title="Next Button" onclick="nextSong()">
					<img src="assets/images/icons/next.png" alt="next">
				</button>

				<button class="controlButton repeat" title="Repeat Button" onclick="repeatSong(true)">
					<img src="assets/images/icons/repeat.png" alt="repeat">
				</button>

				<button class="controlButton repeat active hidden" title="Repeat Button" onclick="repeatSong(false)">
					<img src="assets/images/icons/repeat-active.png" alt="repeat">
				</button>
			</div>

			<div class="progressContainer">
				<span class="processTime current"></span>
				<div class="progressBar">
					<div class="progressBarBk">
						<div class="progress">
						</div>
					</div>
				</div>
				<span class="processTime remaining"></span>
			</div>
		</div>
	</div>
	<div class="right">
		<div class="progressContainer">
			<img src="assets/images/icons/volume.png" alt="volume" class="volumeToggle volume">
			<img src="assets/images/icons/mute.png" alt="volume muted" class="volumeToggle mute">
			<div class="progressBar">
				<div class="progressBarBk">
					<div class="progress">
					</div>
				</div>
			</div>
			<span class="volumeText"></span>
		</div>
	</div>
</div> -->

<!--New Playing Bar 1-->

<div class="nowPlayingBar container-fluid">
	<footer class="nowPlayingBarContainer row">
		<div class="left col-md-3">
			<div class="albumImg">
				<img src="assets/images/square.png" alt="albumImage">
			</div>
			<div class="albumInfo">
				<span class="albumName"></span>
				<span role='link' tabindex="-1" class="artistName"></span>
			</div>

		</div>
		<div class="middle col-md-6">
			<div class="playerControls content">
				<div class="buttons">
					<input id="songId" type="hidden"  />
					<button class="controlButton shuffle" title="Shuffle Button" onclick="shuffleSong(true)">
						<img src="assets/images/icons/shuffle.png" alt="shuffle">
					</button>

					<button class="controlButton shuffle active hiddenBtn" title="Shuffle Button" 
					onclick="shuffleSong(false)">
					<img src="assets/images/icons/shuffle-blue.png" alt="shuffle">
				</button>

				<button class="controlButton prev" title="Previous Button" onclick="prevSong()">
					<img src="assets/images/icons/prev.png" alt="prev">
				</button>

				<button class="controlButton play" title="Play Button" onclick="playSong()">
					<img src="assets/images/icons/play.png" alt="play">
				</button>

				<button class="controlButton pause" title="Pause Button" onclick="pauseSong()">
					<img src="assets/images/icons/pause.png" alt="pause">
				</button>

				<button class="controlButton next" title="Next Button" onclick="nextSong()">
					<img src="assets/images/icons/next.png" alt="next">
				</button>

				<button class="controlButton repeat" title="Repeat Button" onclick="repeatSong(true)">
					<img src="assets/images/icons/repeat.png" alt="repeat">
				</button>

				<button class="controlButton repeat active hiddenBtn" title="Repeat Button"
				onclick="repeatSong(false)">
				<img src="assets/images/icons/repeat-blue.png" alt="repeat">
			</button>
		</div>

		<div class="progressContainer">
			<span class="processTime current"></span>
			<div class="progressBar">
				<div class="progressBarBk">
					<div class="progress">
					</div>
				</div>
			</div>
			<div class="progressSlider active">
				<!--IonSlider-->
				<input type="text" class="track-range-slider" name="my_range" id="trackRange" value=""  >
			</div>
			<span class="processTime remaining"></span>
		</div>
	</div>
</div>
<div class="right col-md-3">
	<!-- <div class="volume-wrapper">
		<div class="progressContainer">
			<img src="assets/images/icons/volume.png" alt="volume" class="volumeToggle volume">
			<img src="assets/images/icons/mute.png" alt="volume muted" class="volumeToggle mute">
			<div class="progressBar">
				<div class="progressBarBk">
					<div class="progress">
					</div>
				</div>
			</div>

		</div>
	</div> -->

	<div class="volume-wrapper">
		<div class="progressContainer">
			<img src="assets/images/icons/volume.png" alt="volume" class="volumeToggle volume">
			<img src="assets/images/icons/mute.png" alt="volume muted" class="volumeToggle mute">
			<div class="progressBar">
				<div class="slidercontainer">
					<input type="text" class="js-range-slider" name="my_range" id="volumeRange" value=""  >
				</div>
			</div>
			<!-- <span class="volumeText"></span> -->
		</div>
	</div>
</div>

</footer>
</div>