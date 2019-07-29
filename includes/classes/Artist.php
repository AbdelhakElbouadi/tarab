<?php 
class Artist{

	private $cnx;
	private $id;
	private $name;

	function __construct($cnx, $id){
		$this->cnx = $cnx;
		$this->id = $id;
		$result = $this->cnx->query("SELECT * FROM artists WHERE id='$this->id'");
		$row = $result->fetch_array();
		$this->name = $row['name'];
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public static function searchForArtist($cnx, $term){
		$result = $cnx->query("SELECT * FROM artists WHERE name LIKE '$term%'");
		//Get the result set back or deceifer it here 
		return $result;
	}

	public static function getNumberOfSongs($cnx, $id){
		$result = $cnx->query("SELECT COUNT(id) as cnt FROM songs WHERE artist='$id'");
		$count = $result->fetch_array()['cnt'];

		return $count;
	}
}

?>