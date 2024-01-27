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
$sql = "CALL calculateplayerweekscore(".$week.")";
$result = mysqli_query($connect -> con, $sql);

while($row = mysqli_fetch_assoc($result)){
    $list[] = $row;
}

while(mysqli_next_result($connect -> con)){;}

foreach($list as $row) {

    if($row['PLAYERID'] != null) {
        $score = 0;

        // Passing yards
        $passingyds = $row['PASSYDS'] > 25 ? $row['PASSYDS'] / 25 : 0;
        $score += $passingyds;

        // Passing TDS
        $passingtds = $row['PASSTDS'] != 0 ? $row['PASSTDS'] * 6 : 0;
        $score += $passingtds;

        // Interceptions
        $passingints = $row['INTS'] > 0 ? -($row['INTS'] * 2) : 0;
        $score += $passingints;

        // Rushing yards
        $rushingyds = $row['RUSHYDS'] > 0 ? $row['RUSHYDS'] / 10 : 0;
        $score += $rushingyds;

        // Rushing TDS
        $rushingtds = $row['RUSHTDS'] > 0 ? $row['RUSHTDS'] * 6 : 0;
        $score += $rushingtds;   

        // Receiving yards
        $rushingyds = $row['RECYDS'] > 0 ? $row['RECYDS'] / 10 : 0;
        $score += $rushingyds;

        // Receiving TDS
        $rushingtds = $row['RECTDS'] > 0 ? $row['RECTDS'] * 6 : 0;
        $score += $rushingtds; 

        // Fumbles
        $fumbles = $row['FUMB'] > 0 ? -($row['FUMB'] * 2) : 0;
        $score += $fumbles;

        // Kick30
        $kick30 = $row['KICK30'] > 0 ? $row['KICK30'] * 3 : 0;
        $score += $kick30;

        // Kick40
        $kick40 = $row['KICK40'] > 0 ? $row['KICK40'] * 4 : 0;
        $score += $kick40;

        // Kick50
        $kick50 = $row['KICK50'] > 0 ? $row['KICK50'] * 5 : 0;
        $score += $kick50;

        // Kickextra
        $kickextra = $row['EXTRAKICK'] > 0 ? $row['EXTRAKICK'] * 1 : 0;
        $score += $kickextra;

        $newsql = "CALL setplayerweekscore(".$row['PLAYERID'].",".$week.",".$score.")";
        echo $newsql.PHP_EOL;

        $submitscore = mysqli_query($connect -> con, $newsql);
    }
}

?>