
$(document).ready(function(){
	audio = document.createElement("audio");
	player = new Player(audio);
	currentPlayingList = <?php echo $jsonSongsArray; ?>; 
	setTrack(currentPlayingList[0], currentPlayingList, false);
	console.log(currentPlayingList);

	$(document).on('mousedown mousemove touchdown touchmove', function(e){
		e.preventDefault();
	});

	$(audio).on("canplay", function(){
		$(".processTime.current").text("0:00");
		$(".processTime.remaining").text(formatTime(player.duration));
		volumeInitialize();
	});

	$(audio).on('timeupdate', function(){
		$(".processTime.current").text(formatTime(player.currentTime));
		$(".processTime.remaining").text(formatTime(player.remainingTime));
		var actual = Math.round(player.currentTime*100/player.duration);
		$(".middle .progress").css({"width": actual+"%"});
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
			console.log('Are you deaf or what?');
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
			console.log("Not Muted");
		}else{
			player.setVolume(volume);
			player.setMuted(false);
			$(".volumeToggle.volume").show();
			$(".volumeToggle.mute").hide();
			volumeInitialize();
			console.log("Muted");
		}
	});

	var mouseDown = false;
	$(".middle .progressBarBk").on("mousemove", function(e){
		if(mouseDown){
			if(!player.notPlaying()){
				timeOffset(e, this);	
			}
		}
	});

	$(".middle  .progressBarBk").on("mousedown", function(e){
		mouseDown = true;
	});

	$(".middle  .progressBarBk").on("mouseup", function(e){
		if(!player.notPlaying())
			timeOffset(e, this);	
	});

	
	$(".right .progressBarBk").on("mousemove", function(e){
		if(mouseDown){
			volumeOffset(e, this);
		}
	});

	$(".right  .progressBarBk").on("mousedown", function(e){
		mouseDown = true;
	});

	$(".right  .progressBarBk").on("mouseup", function(e){
		volumeOffset(e, this);	
	});

	$(document).on("mouseup", function(e){
		mouseDown = false;
	});
});

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
	var width = $(".right .progressBarBk").width();
	var percentage = player.getVolume();

	$(".right .progress").css({"width" : width*percentage});
	$(".right .progress").attr('title', Math.round(percentage*100) + "%");
	$(".volumeText").text(Math.round(percentage*100) + "%");
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

	$.post("includes/handlers/ajax/getSongJson.php", {id: trackId})
	.done(function(data){
		songObject = JSON.parse(data);
		albumId = songObject.album;
		artistId = songObject.artist; 
		var plays = songObject.plays;

		$.post("includes/handlers/ajax/getAlbumJson.php", {id: albumId})
		.done(function(data){
			var albumObject = JSON.parse(data);
			$(".left .albumImg img").attr("src", albumObject.artworkPath);
			$(".left .albumInfo .albumName").text(albumObject.title);
		});

		$.post("includes/handlers/ajax/getArtistJson.php", {id: artistId})
		.done(function(data){
			var artistObject = JSON.parse(data);
			$(".left .albumInfo .artistName").text(artistObject.name);
		});

		player.setTrack(songObject.path);
		updateSongPlays(trackId, plays);

		$("#songId").val(trackId);

		if(newPlayingList == null){
			$.post("includes/handlers/ajax/getAlbumSongsJson.php", {id: albumId})
			.done(function(data){
				albumSongs = JSON.parse(data);
				console.log(albumSongs);
			});
		}else{

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
