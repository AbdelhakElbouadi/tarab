<?php 

	require_once 'Artist.php';
	require_once 'Genre.php';
	require_once 'Album.php';

	class Song{

		private $cnx;
		private $id;
		private $title;
		private $genre;
		private $artist;
		private $album;
		private $duration;
		private $path;
		private $albumOrder;
		private $plays;

		function __construct($cnx, $id){
			$this->cnx = $cnx;
			$this->id = $id;
			$result = $this->cnx->query("SELECT * FROM songs WHERE id='$this->id'");
			$row = $result->fetch_array();
			$this->title = $row['title'];
			$this->genre = new Genre($this->cnx, $row['genre']);
			$this->artist = new Artist($this->cnx, $row['artist']);
			$this->album = new Album($this->cnx, $row['album']);
			$this->duration = $row['duration'];
			$this->path = $row['path'];
			$this->albumOrder = $row['albumOrder'];
			$this->plays = $row['plays'];
		}

		public function getId(){
			return $this->id;
		}

		public function getTitle(){
			return $this->title;
		}

		public function getGenre(){
			return $this->genre;
		}

		public function getArtist(){
			return $this->artist;
		}

		public function getAlbum(){
			return $this->album;
		}

		public function getDuration(){
			return $this->duration;
		}
		public function getPath(){
			return $this->path;
		}

		public function getAlbumOrder(){
			return $this->albumOrder;
		}

		public function getPlays(){
			return $this->plays;
		}

		public static function searchForSong($cnx, $term){
			$result = $cnx->query("SELECT id FROM songs WHERE title LIKE '$term%'");
			$songsId = array();
			if($result->num_rows > 0){
				while($row = $result->fetch_array()){
					array_push($songsId, $row['id']);
				}
			}

			return $songsId;
		}
	}
?>