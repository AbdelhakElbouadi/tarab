<?php 

	class Genre{
		private $cnx;
		private $id;
		private $name;

		function __construct($cnx, $id){
			$this->cnx = $cnx;
			$this->id = $id;
			$result = $this->cnx->query("SELECT * FROM genre WHERE id='$this->id'");
			$row = $result->fetch_array();
			$this->name = $row['name'];
		}

		public function getId(){
			return $this->id;
		}

		public function getName(){
			return $this->name;
		}
	}
?>