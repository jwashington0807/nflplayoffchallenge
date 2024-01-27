<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('../secure/dbconnection.php');

// Connect to DB
$connect = new DBConnection();
$connect -> connectToDatabase();

// Week 
$week = 2;

// Array to hold values
$list = array();

// Call the stored procedure and list of all players from week
$sql = "CALL getweekuserteams(".$week.")";
$result = mysqli_query($connect -> con, $sql);

while($row = mysqli_fetch_assoc($result)){
    $list[] = $row;
}

while(mysqli_next_result($connect -> con)){;}

foreach($list as $row) {

    $score = 0;
    $sql = "CALL collectteamscores(".$row["ID"].")";
    $result = mysqli_query($connect -> con, $sql);

}

        // Close SQL Connection
        $connect -> closeConnection();
?>