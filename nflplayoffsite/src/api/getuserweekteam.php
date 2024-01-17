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
            $team -> qb = $result['QB1'];
            $team -> qbid = $result['rosterqb'];
            $team -> wr1 = $result['WR1'];
            $team -> wr1id = $result['rosterwr1'];
            $team -> wr2 = $result['WR2'];
            $team -> wr2id = $result['rosterwr2'];
            $team -> rb1 = $result['RB1'];
            $team -> rb1id = $result['rosterrb1'];
            $team -> rb2 = $result['RB2'];
            $team -> rb2id = $result['rosterrb2'];
            $team -> te = $result['TE'];
            $team -> teid = $result['rosterte'];
            $team -> pk = $result['PK'];
            $team -> pkid = $result['rosterk'];
            $team -> def = $result['DEF'];
            $team -> defid = $result['rosterdef'];
        }

        echo json_encode($team);
    }
}
else
{
    exit();
}

?>