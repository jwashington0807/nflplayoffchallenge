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
$week = 3;

// Array to hold values
$list = array();

// Call the stored procedure and list of all players from week
$sql = "CALL calculateteamweekscore(".$week.")";
$result = mysqli_query($connect -> con, $sql);

while($row = mysqli_fetch_assoc($result)){
    $list[] = $row;
}

while(mysqli_next_result($connect -> con)){;}

foreach($list as $row) {

    if($row['TEAMID'] != null) {
        $score = 0;

        // Sacks
        $sacks = $row['SACKS'] > 0 ? $row['SACKS'] : 0;
        $score += $sacks;

        // Fumbles
        $fumbles = $row['FUMBLES'] > 0 ? $row['FUMBLES'] * 2 : 0;
        $score += $fumbles;

        // Touchdowns
        $tds = $row['TDS'] > 0 ? $row['TDS'] * 6 : 0;
        $score += $tds;

        // Allowed Yards
        $allowyards = $row['ALLOWYDS'] > 0 ? $row['ALLOWYDS'] : 0;
        $score += $allowyards;

        // Allowed Points
        if($row['ALLOWPTS'] == 0){
            $allowpoints = 10;
        }
        else if($row['ALLOWPTS'] <= 6){
            $allowpoints = 7;
        }
        else if($row['ALLOWPTS'] <= 13){
            $allowpoints = 4;
        }
        else if($row['ALLOWPTS'] <= 20){
            $allowpoints = 1;
        }
        else if($row['ALLOWPTS'] <= 27){
            $allowpoints = 0;
        }
        else if($row['ALLOWPTS'] <= 34){
            $allowpoints = -1;
        }
        else{
            $allowpoints = -4;
        }

        $score += $allowpoints;   

        // Interceptions
        $ints = $row['INTS'] > 0 ? $row['INTS'] * 2 : 0;
        $score += $ints;

        // Safety
        $safety = $row['SAFETY'] > 0 ? $row['SAFETY'] * 2 : 0;
        $score += $safety;

        $newsql = "CALL setteamweekscore(".$row['TEAMID'].",".$week.",".$score.")";
        echo $newsql.PHP_EOL;

        $submitscore = mysqli_query($connect -> con, $newsql);
    }
}

        // Close SQL Connection
        $connect -> closeConnection();

?>