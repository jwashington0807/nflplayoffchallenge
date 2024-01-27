<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('../secure/dbconnection.php');
require('../models/playerstats.php');

// Connect to DB
$connect = new DBConnection();
$connect -> connectToDatabase();

// Initialize array to hold objects
$players = [];

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
                    $team = $events["gamepackageJSON"]["boxscore"]["players"][$i];

                    // Get the Stats
                    $stats = $team["statistics"];

                    // Iterate through every stat category
                    foreach($stats as $statbox) {

                        switch($statbox["name"]) {
                            case "passing":

                                foreach($statbox["athletes"] as $athlete) {

                                    $key = findObjectById($athlete["athlete"]["id"], $players);

                                    if($key != -1) {
                                        // Found existing Athlete
                                        $players[$key] -> passingyards = $athlete["stats"][1];
                                        $players[$key] -> passingtouchdowns = $athlete["stats"][3];
                                        $players[$key] -> interceptions = $athlete["stats"][4];
                                    }
                                    else {
                                        // New Object to hold all data
                                        $playerstats = new PlayerStats();  
                                        
                                        $playerstats -> player = $athlete["athlete"]["displayName"];
                                        $playerstats -> playerid = $athlete["athlete"]["id"];
                                        $playerstats -> team = $teamname;
                                        $playerstats -> passingyards = $athlete["stats"][1];
                                        $playerstats -> passingtouchdowns = $athlete["stats"][3];
                                        $playerstats -> interceptions = $athlete["stats"][4];

                                        array_push($players, $playerstats);
                                    }
                                }

                                break;
                            case "rushing":

                                foreach($statbox["athletes"] as $athlete) {

                                    $key = findObjectById($athlete["athlete"]["id"], $players);

                                    if($key != -1) {
                                        // Found existing Athlete
                                        $players[$key] -> rushingyards = $athlete["stats"][1];
                                        $players[$key] -> rushingtouchdowns = $athlete["stats"][3];
                                    }
                                    else {
                                        // New Object to hold all data
                                        $playerstats = new PlayerStats();  
                                        
                                        $playerstats -> player = $athlete["athlete"]["displayName"];
                                        $playerstats -> playerid = $athlete["athlete"]["id"];
                                        $playerstats -> team = $teamname;
                                        $playerstats -> rushingyards = $athlete["stats"][1];
                                        $playerstats -> rushingtouchdowns = $athlete["stats"][3];

                                        array_push($players, $playerstats);
                                    }
                                }

                                break;
                            case "receiving":

                                foreach($statbox["athletes"] as $athlete) {

                                    $key = findObjectById($athlete["athlete"]["id"], $players);

                                    if($key != -1) {
                                        // Found existing Athlete
                                        $players[$key] -> receptions = $athlete["stats"][0];
                                        $players[$key] -> receivingyards = $athlete["stats"][1];
                                        $players[$key] -> receivingtouchdowns = $athlete["stats"][3];
                                    }
                                    else {
                                        // New Object to hold all data
                                        $playerstats = new PlayerStats();  
                                        
                                        $playerstats -> player = $athlete["athlete"]["displayName"];
                                        $playerstats -> playerid = $athlete["athlete"]["id"];
                                        $playerstats -> team = $teamname;
                                        $playerstats -> receptions = $athlete["stats"][0];
                                        $playerstats -> receivingyards = $athlete["stats"][1];
                                        $playerstats -> receivingtouchdowns = $athlete["stats"][3];

                                        array_push($players, $playerstats);
                                    }
                                }

                                break;
                            case "fumbles":

                                foreach($statbox["athletes"] as $athlete) {

                                    $key = findObjectById($athlete["athlete"]["id"], $players);

                                    if($key != -1) {
                                        // Found existing Athlete
                                        $players[$key] -> fumbleslost = $athlete["stats"][1];
                                    }
                                    else {
                                        // New Object to hold all data
                                        $playerstats = new PlayerStats();  
                                        
                                        $playerstats -> player = $athlete["athlete"]["displayName"];
                                        $playerstats -> playerid = $athlete["athlete"]["id"];
                                        $playerstats -> team = $teamname;
                                        $playerstats -> fumbleslost = $athlete["stats"][1];

                                        array_push($players, $playerstats);
                                    }
                                }

                                break;
                            case "kicking":

                                foreach($statbox["athletes"] as $athlete) {

                                    $key = findObjectById($athlete["athlete"]["id"], $players);

                                    if($key != -1) {
                                        // Found existing Athlete
                                        $players[$key] -> extrapointkicks = $athlete["stats"][3];
                                    }
                                    else {
                                        // New Object to hold all data
                                        $playerstats = new PlayerStats();  
                                        
                                        $playerstats -> player = $athlete["athlete"]["displayName"];
                                        $playerstats -> playerid = $athlete["athlete"]["id"];
                                        $playerstats -> team = $teamname;
                                        $playerstats -> extrapointkicks = $athlete["stats"][3];

                                        array_push($players, $playerstats);
                                    }
                                }

                                break;
                            default
                                ;
                        }
                    }
                }
            }
        }
    }

    //Iterate through array and store stats in database
    foreach($players as $player) {
        
        // Last Second Cleanup
        $player -> passingyards == '' ? $player -> passingyards = 0 : $player -> passingyards;
        $player -> passingtouchdowns == '' ? $player -> passingtouchdowns = 0 : $player -> passingtouchdowns;
        $player -> interceptions == '' ? $player -> interceptions = 0 : $player -> interceptions;
        $player -> rushingyards == '' ? $player -> rushingyards = 0 : $player -> rushingyards;
        $player -> rushingtouchdowns == '' ? $player -> rushingtouchdowns = 0 : $player -> rushingtouchdowns;
        $player -> receivingyards == '' ? $player -> receivingyards = 0 : $player -> receivingyards;
        $player -> receivingtouchdowns == "" ? $player -> receivingtouchdowns = 0 : $player -> receivingtouchdowns;
        $player -> fumbleslost == '' ? $player -> fumbleslost = 0 : $player -> fumbleslost;
        $player -> extrapointkicks == '' ? $player -> extrapointkicks = 0 : $player -> extrapointkicks = preg_replace('/^\D+|\d+\K.*/', '', $player -> extrapointkicks);
        
        // Call the stored procedure and get the result
        $sql = "CALL addweeklystats(".$week." , "
                                .$player -> playerid." , \""
                                .$player -> player."\" , '"
                                .$player -> team."' , "
                                .$player -> passingyards." , "
                                .$player -> passingtouchdowns." , "
                                .$player -> interceptions." , "
                                .$player -> rushingyards." , "
                                .$player -> rushingtouchdowns." , "
                                .$player -> receivingyards." , "
                                .$player -> receivingtouchdowns." , "
                                .$player -> fumbleslost." , "
                                .$player -> extrapointkicks.")";
        $result = mysqli_query($connect -> con, $sql);

        echo $sql.PHP_EOL;
    }
}
else {
    echo "No results for events";
}

function findObjectById($id, $array){

    $counter = 0;

    foreach ($array as $element ) {
        if ( $id == $element -> playerid ) {
            return $counter;
        }
        $counter++;
    }

    return -1;
}

?>