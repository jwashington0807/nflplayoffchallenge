<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('../secure/dbconnection.php');

// Connect to DB
$connect = new DBConnection();
$connect -> connectToDatabase();

// Variables to use
$week = 2;
$list = array();

// Call the stored procedure and get the result
$sql = "CALL getpushteams(".$week.")";
$result = mysqli_query($connect -> con, $sql);

while($row = mysqli_fetch_assoc($result)){
  $list[] = $row;
}

while(mysqli_next_result($connect -> con)){;}

foreach($list as $row) {
    $newsql = "CALL setpushteams(".$row['fk_userteamid'].",".$week.")";
    $newresult = mysqli_query($connect -> con, $newsql);
}

?>