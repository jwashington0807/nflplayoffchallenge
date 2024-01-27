<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('../secure/dbconnection.php');
require('../models/teamstats.php');

// Connect to DB
$connect = new DBConnection();
$connect -> connectToDatabase();

// Initialize array to hold objects
$teams = [];

$team = "";

// Season Year
$year = 2023;

// 3: Postseason
$seasontype = 3;

// Week in season type
$week = 2;

// Wildcard Events
$url = "https://site.api.espn.com/apis/site/v2/sports/football/nfl/scoreboard?dates=".$year."&seasontype=".$seasontype."&week=".$week."";

// Box Score 
// https://cdn.espn.com/core/nfl/boxscore?xhr=1&gameId={EVENT_ID}

$response = file_get_contents($url);

// Check for response
if($response) {
    $output = json_decode($response);

    // Get Defense Stats
    foreach($output -> events as $event) {

        if($event -> id != null) {

            // Look up the event by using the ID
            $eventurl = "https://cdn.espn.com/core/nfl/boxscore?xhr=1&gameId=".$event -> id."";

            // Make GET call
            $eventresponse = file_get_contents($eventurl);

            // Check if response is valid
            if($eventresponse) {

                // Decode JSON Response
                $events = json_decode($eventresponse, true);
                
                // There are 2 teams and ESPN divided them by array 0 and 1
                for($i = 0; $i <= 1; $i++) {

                    // Get the Team
                    $teamname = $events["gamepackageJSON"]["boxscore"]["players"][$i]["team"]["displayName"];
                    $teamid = $events["gamepackageJSON"]["boxscore"]["players"][$i]["team"]["id"];
                    $team = $events["gamepackageJSON"]["boxscore"]["players"][$i];

                    // Get the Stats
                    $stats = $team["statistics"];

                    // Iterate through every stat category
                    foreach($stats as $statbox) {

                        switch($statbox["name"]) {
                            case "interceptions":

                                $key = findObjectById($teamid, $teams);

                                if($key != -1) {
                                    // Found existing Athlete
                                    $teams[$key] -> interceptions = $statbox["totals"] == null ? $teams[$key] -> interceptions = 0 : $teams[$key] -> interceptions = $statbox["totals"][0];
                                }
                                else {
                                    // New Object to hold all data
                                    $teamstats = new TeamStats();  
                                    
                                    $teamstats -> team = $teamname;
                                    $teamstats -> teamid = $teamid;
                                    $statbox["totals"] == null ? $teamstats -> interceptions = 0 : $teamstats -> interceptions = $statbox["totals"][0];

                                    array_push($teams, $teamstats);
                                }

                                break;
                            case "fumbles":

                                $key = findObjectById($teamid, $teams);

                                if($key != -1) {
                                    // Found existing Athlete
                                    $teams[$key] -> fumbles = $statbox["totals"] == null ? $teams[$key] -> fumbles == 0 : $teams[$key] -> fumbles = $statbox["totals"][2];
                                }
                                else {
                                    // New Object to hold all data
                                    $teamstats = new TeamStats();  
                                
                                    $teamstats -> team = $teamname;
                                    $teamstats -> teamid = $teamid;
                                    $statbox["totals"] == null ? $teamstats -> fumbles == 0 : $teamstats -> fumbles = $statbox["totals"][2];

                                    array_push($teams, $teamstats);
                                }

                                break;
                            case "defensive":

                                $key = findObjectById($teamid, $teams);

                                if($key != -1) {
                                    // Found existing Athlete
                                    $teams[$key] -> sacks = intval($statbox["totals"][2]);
                                    $teams[$key] -> touchdowns = intval($statbox["totals"][6]);
                                }
                                else {
                                    // New Object to hold all data
                                    $teamstats = new TeamStats();  
                                
                                    $teamstats -> team = $teamname;
                                    $teamstats -> teamid = $teamid;
                                    $teamstats -> sacks = intval($statbox["totals"][2]);
                                    $teamstats -> touchdowns = intval($statbox["totals"][6]);

                                    array_push($teams, $teamstats);
                                }

                                break;

                                foreach($statbox["athletes"] as $defense) {

                                    $key = findObjectById($defense["athlete"]["id"], $players);

                                    if($key != -1) {
                                        // Found existing Athlete
                                        $teams[$key] -> extrapointkicks = $defense["stats"][3];
                                    }
                                    else {
                                        // New Object to hold all data
                                        $teamstats = new teamstats();  
                                        
                                        $teamstats -> player = $defense["athlete"]["displayName"];
                                        $teamstats -> playerid = $defense["athlete"]["id"];
                                        $teamstats -> team = $teamname;
                                        $teamstats -> extrapointkicks = $defense["stats"][3];

                                        array_push($players, $teamstats);
                                    }
                                }

                                break;
                            default
                                ;
                        }
                    }
                }
            }

            // Get Scores from event
            $scoreboard = "https://site.api.espn.com/apis/site/v2/sports/football/nfl/scoreboard?dates=".$year."&seasontype=".$seasontype."&week=".$week."";

            // Make GET call
            $scoreresponse = file_get_contents($scoreboard);

            if($scoreresponse) {

                // Decode JSON Response
                $scores = json_decode($scoreresponse, true);

                // Get all events
                $events = $scores["events"];

                // Loop through events
                foreach($events as $event) {

                    $competitions = $event["competitions"][0]["competitors"];

                    $team1score = 0;
                    $team1id = 0;

                    $team2score = 0;
                    $team2id = 0;

                    // There are 2 teams and ESPN divided them by array 0 and 1
                    for($i = 0; $i <= 1; $i++) {

                        if($i == 0) {
                            $team1score = $competitions[$i]["score"];
                            $team1id = $competitions[$i]["id"];
                        }
                        else {
                            $team2score = $competitions[$i]["score"];
                            $team2id = $competitions[$i]["id"];
                        }
                    }

                    $key1 = findObjectById($team1id, $teams);
                    if($key1 != -1) {
                        // Found existing Athlete
                        $teams[$key1] -> allowedpoints = $team2score;
                    }

                    $key2 = findObjectById($team2id, $teams);
                    if($key2 != -1) {
                        // Found existing Athlete
                        $teams[$key2] -> allowedpoints = $team1score;
                    }
                }
            }
        }
    }

    //Iterate through array and store stats in database
    foreach($teams as $team) {
        
        // Call the stored procedure and get the result
        $sql = "CALL addweeklydefstats(".$week." , "
                                .$team -> teamid." , '"
                                .$team -> team."' , "
                                .$team -> sacks." , "
                                .$team -> fumbles." , "
                                .$team -> interceptions." , "
                                .$team -> touchdowns." , "
                                .$team -> allowedyards." , "
                                .$team -> allowedpoints." , "
                                .$team -> safeties.")";
        $result = mysqli_query($connect -> con, $sql);

        echo $sql.PHP_EOL;
    }

    // Close SQL Connection
    $connect -> closeConnection();
}
else {
    echo "No results for events";
}

function findObjectById($id, $array){

    $counter = 0;

    foreach ($array as $element ) {
        if ( $id == $element -> teamid ) {
            return $counter;
        }
        $counter++;
    }

    return -1;
}

?>