<?php 

	require_once 'Artist.php';
	require_once 'Genre.php';

	class Album{

		private $cnx;
		private $id;
		private $title;
		private $genre;
		private $artist;
		private $artworkPath;

		function __construct($cnx, $id){
			$this->cnx = $cnx;
			$this->id = $id;
			$result = $this->cnx->query("SELECT * FROM albums WHERE id='$this->id'");
			$row = $result->fetch_array();
			$this->title = $row['title'];
			$this->genre = new Genre($this->cnx, $row['genre']);
			$this->artist = new Artist($this->cnx, $row['artist']);
			$this->artworkPath = $row['artworkPath'];
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

		public function getArtworkPath(){
			return $this->artworkPath;
		}

		public function getSongsId(){
			$result = $this->cnx->query("SELECT id FROM songs WHERE album = '$this->id'");
			$idArray = array();
			while($row = $result->fetch_array()){
				array_push($idArray, $row['id']);
			}

			return $idArray;
		}

		public function getNumberOfSongs(){
			$result = $this->cnx->query("SELECT id FROM songs WHERE album = '$this->id'");
			return $result->num_rows != 0 ? $result->num_rows : 0;
		}

		public static function searchForAlbum($cnx, $term){
			$result = $cnx->query("SELECT * FROM albums WHERE title LIKE '$term%'");
			return $result;
		}
	}
?>