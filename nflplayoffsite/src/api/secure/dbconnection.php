<?php

class DBConnection
{
	public $con;
	public $sql;
	public $id;
	public $mileage;

	public function connectToDatabase(){
		
		// Create connection
		//$this -> con = mysqli_connect("localhost","justmejt_nfluser","Kima2022!","justmejt_NFLPlayoffChallenge")
		$this -> con = mysqli_connect("localhost:3306","root","","justmejt_nflplayoffchallenge")
			or die ('Failed to connect to database');

		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}
	
	public function closeConnection() {

		// Close connections
		mysqli_close($this -> con);

	}
}

?>