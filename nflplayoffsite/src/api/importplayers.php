<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/user.php');

// Connect to DB
$connect = new DBConnection();
$connect -> connectToDatabase();

$teamid = 0;

for($i = 1; $i <= 33; $i++) {
    $url = 'https://sports.core.api.espn.com/v2/sports/football/leagues/nfl/seasons/2023/teams/'.strval($i).'/athletes?limit=200';
    $response = file_get_contents($url);

    if($response) {
        $output = json_decode($response);
        
        foreach ($output -> items as $item){

            $playeritem = get_object_vars($item);
            $url = $playeritem['$ref'];
            $response = file_get_contents($url);

            $playeroutput = json_decode($response);
            $playerteam = $playeroutput -> team;

            if($playerteam) {
                $teamitem = get_object_vars($playerteam);

                $teamurl = $teamitem['$ref'];
                $response = file_get_contents($teamurl);

                $teamoutput = json_decode($response);
                $name = $teamoutput -> displayName;

                // Get Team ID
                $sql = "SELECT teamsid FROM teams WHERE teamname = '$name'";
                $result = mysqli_query($connect -> con, $sql);

                while($row = $result->fetch_assoc()) {                   
                    $teamid = $row['teamsid'];
                }

                if(validateposition($playeroutput -> position -> abbreviation) && $teamid != 0) {
                    /*$inserttsql = "INSERT INTO players (player, team, position, active, year) 
                    VALUES ('".mysqli_real_escape_string($connect -> con, $playeroutput -> fullName)."',".$teamid.",'".$playeroutput -> position -> abbreviation."',1,2023)";
                    $result = mysqli_query($connect -> con, $inserttsql);*/
                }

                $teamid = 0;
            }
        }
    }
}

function validateposition($position) {

    switch ($position) {
        case 'QB':
        case 'RB':
        case 'WR':    
        case 'TE':
        case 'PK':
            return true;
          break;
        default:
          return false;
      }
}
?>