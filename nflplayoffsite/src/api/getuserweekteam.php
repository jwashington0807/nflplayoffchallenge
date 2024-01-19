<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/team.php');


if($_GET["week"] && $_GET["useremail"]) 
{
    function validate($data){

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $week = validate($_GET["week"]);
    $email = validate($_GET["useremail"]);

    if (empty($week) || empty($email)) {
        exit();
    }
    else
    {
        // Connect to DB
        $connect = new DBConnection();
        $connect -> connectToDatabase();

        // Create a new User Object to return
        $team = new Team();

        // Check to see if email is available
        $sql = "CALL getuserteam(".$week.",'".strval($email)."')";
        $response = mysqli_query($connect -> con, $sql);

        while($result = mysqli_fetch_array($response)) {

            // Insert the values
            $team -> userid = $result['userid'];
            $team -> qbid = $result['QB1'];
            $team -> wr1id = $result['WR1'];
            $team -> wr2id = $result['WR2'];
            $team -> rb1id = $result['RB1'];
            $team -> rb2id = $result['RB2'];
            $team -> teid = $result['TE'];
            $team -> pkid = $result['PK'];
            $team -> defid = $result['DEF'];
        }

        echo json_encode($team);
    }
}
else
{
    exit();
}

?>