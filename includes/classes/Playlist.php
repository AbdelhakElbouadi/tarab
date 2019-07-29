<?php 

	require_once 'User.php';

	class Playlist{

		private $cnx;
		private $id;
		private $name;
		private $owner;

		function __construct($cnx, $id){
			$this->cnx = $cnx;
			$this->id = $id;
			$result = $this->cnx->query("SELECT * FROM playlists WHERE id='$this->id'");
			$row = $result->fetch_array();
			$this->name = $row['name'];
			$this->owner = new User($this->cnx, $row['owner']);
		}

		public function getId(){
			return $this->id;
		}

		public function getName(){
			return $this->name;
		}

		public function getOwner(){
			return $this->owner;
		}

		public function getSongsId(){
			$result = $this->cnx->query("SELECT song FROM playlistSongs WHERE playlist = '$this->id'");
			$idArray = array();
			while($row = $result->fetch_array()){
				array_push($idArray, $row['song']);
			}

			return $idArray;
		}

		public function getNumberOfSongs(){
			$result = $this->cnx->query("SELECT id FROM playlistSongs WHERE playlist = '$this->id'");
			return $result->num_rows != 0 ? $result->num_rows : 0;
		}

		public static function getPlaylistsDropdown($cnx, $userId){
			$dropdown = "<select class='popitem playlist'><option value=''>Add to playlist</option>";
			$result = $cnx->query("SELECT id , name FROM playlists WHERE owner='$userId'");
			if($cnx->errno){
				die("Error:".$cnx->error);
			}
			while($row = $result->fetch_array()){
				$dropdown = $dropdown."<option value='".$row['id']."'>".$row['name']."</option>";
			}
			
			return $dropdown."</select>";
		}
	}
?>