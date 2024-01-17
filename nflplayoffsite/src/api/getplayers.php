<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/player.php');

// Connect to DB
$connect = new DBConnection();
$connect -> connectToDatabase();

// Call the stored procedure and get the result
$sql = "CALL getplayers()";
$result = mysqli_query($connect -> con, $sql);

$output = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get the number of rows returned by query
//$rowcount = mysqli_num_rows($rows);

// Initialize array to hold objects
$players = [];

// Check if we got a match, if not then invalid
/*while($row = mysqli_fetch_array($rows))
{
    
    $player = new Player();
    $player -> playerid = $row['playerid'];
	$player -> player = $row['player'];
	$player -> team = $row['team'];
	$player -> position = $row['position'];
	$player -> teamname = $row['teamname'];
    $player -> color = $row['primarycolor'];

    array_push("data": $players, $player);
    //$players[] = $row;
}*/

// Return JSON back to client
echo json_encode($output);


?>