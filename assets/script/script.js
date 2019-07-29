var player;
var audio;
var currentPlayingList = [];
var currentAlbumId;
var volume;
var repeat = false;
var shuffle = false;
var oldPlayingList = [];
var albumSongs;
var navigationState = [];
var timer;
var volumeSlider;
var trackSlider;
var updatingSlide = false;

function formatTime(seconds){
	var lzero  = "";
	var min = Math.round(seconds/60);
	var sec = Math.round(seconds%60);
	if(sec < 10){
		lzero = "0";
	}
	var res = min + ":" + lzero + sec;   

	return res;
}

class Player{
	constructor(audio){
		this.audio = audio;
	}

	setTrack(src){
		this.audio.src = src;
	}

	play(){
		this.audio.play();
	}

	pause(){
		this.audio.pause();
	}

	load(){
		this.audio.load();
	}

	notPlaying(){
		return this.audio.paused || (this.audio.currentTime == 0);
	}

	autoPlay(auto){
		return this.audio.autoplay = auto;
	}

	setCurrentTime(time){
		this.audio.currentTime = time;
	}

	setVolume(volume){
		this.audio.volume = volume;
	}

	getVolume(){
		return this.audio.volume;
	}

	isMuted(){
		return this.audio.muted;
	}

	setMuted(muted){
		this.audio.muted = muted;
	}

	get currentTime(){
		return this.audio.currentTime;
	}

	get remainingTime(){
		return (this.audio.duration - this.audio.currentTime);
	}

	get duration(){
		return this.audio.duration;
	}
}

$(document).on("change", ".popitem.playlist", function(){
	var playlistId = $(this).val();
	var songId = $(this).prev(".songId").val();
	//console.log("PlaylistId=>" + playlistId + " SongId=>" + songId);
	$.post("includes/handlers/ajax/addToPlaylist.php", {playlist: playlistId, song: songId}
	/*		,function(xhr, status, error){
			console.log('XHR=>' + xhr);
			console.log('Status=>' + status);
			console.log('Error=>' + error);
		}*/)
	.done(function(data){
		hidePopupMenu();
		$(".popitem.playlist").val("");
	});
});

$(document).on("mouseup", function(e){
	mouseDown = false;
});

$(window).scroll(function(){
	hidePopupMenu();
});

$(document).click(function(click){
	var target = $(click.target);

	if(!target.hasClass('popitem') && !target.hasClass('optionsButton')){
		hidePopupMenu();
	}
});

function openPage(url){
	if(timer != null){
		clearTimeout(timer);
	}
	var addon = " #mainWrapper";
	var encodedUrl = encodeURI(url);
	console.log('Encoded URL=>' + encodedUrl);
	$(".mainContainer").load(encodedUrl + addon, function(data, status, xhr){
			//console.log("The Returned Data=>" + data);
		});
	$("body").scrollTop(0);
	history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId){
	var songId = $(button).prevAll(".songId").val();
	console.log("The PlaylistId=>" + playlistId + " SongId=>" + songId);
	$.post("includes/handlers/ajax/removeFromPlaylist.php", {playlist: playlistId, song: songId})
	.done(function(data){
		if(data){
			hidePopupMenu();
			var path = window.location.href;
			var currentPage = path.substring(path.lastIndexOf('/') + 1);
			openPage(currentPage);
		}
	});
}

function hidePopupMenu(){
	var menu = $(".popupmenu");
	if(menu.css("display") != "none"){
		menu.css("display", "none");
		menu.hide();
	}
}

function showPopupMenu(ele){
	var scroll = $(window).scrollTop();
	var x = $(ele).offset().left;
	var y = $(ele).offset().top;
	var width = $(".popupmenu").width();

	var songId = $(ele).prevAll(".songId").val();
	$(".popupmenu .songId").val(songId);

	console.log("PageX=>" + x);
	console.log("PageY=>" + y);
	$(".popupmenu").css({"top": (y - scroll)+"px", "left": (x-width) + "px", "display" : "inline"});
	$(".popupmenu").show();
}


function deletePlaylist(playlist){
	var agree = confirm("Are you sure?");
	if(agree){
		$.post("includes/handlers/ajax/deletePlaylist.php", {id: playlist})
		.done(function(data){
			if(data == "success"){
				window.history.back();
			}
		});
	}
}

function createNewPlaylist(){
	var playlist = prompt('Create New Playlist');
		$.post("includes/handlers/ajax/createNewPlaylist.php", {name: playlist, owner: user})
		.done(function(data){
			if(data){
				console.log('Data=>' + data);
				var outerDiv = document.createElement("div");
				var imgDiv = document.createElement("div");
				var infoDiv = document.createElement("div");

				$(outerDiv).attr('class', 'gridViewItem');
				$(outerDiv).attr('role', 'link');
				var page = "openPage('playlist.php?id=" + data + "')";
				$(outerDiv).attr('onclick', page);

				$(imgDiv).attr('class', 'gridViewImg');
				var img = document.createElement("img");
				$(img).attr('src', 'assets/images/icons/playlist.png');
				$(img).attr('alt', 'Playlist Image');
				$(imgDiv).append(img);

				$(infoDiv).attr('class', 'gridViewInfo');
				var span = document.createElement("span");
				$(span).text(playlist);
				$(infoDiv).append(span);

				$(outerDiv).append(imgDiv);
				$(outerDiv).append(infoDiv);

				$(".playlistContent").append(outerDiv);
			}else{
				console.log(JSON.stringify(data));
			}
		});
	}


