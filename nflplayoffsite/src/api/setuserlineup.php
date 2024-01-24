<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/user.php');

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)) 
{
    $request = json_decode($postdata);

    $email = $request -> userTeam -> email;
    $week = $request -> userTeam -> week;
    $qbid = $request -> userTeam -> qbid;
    $wr1id = $request -> userTeam -> wr1id;
    $wr2id = $request -> userTeam -> wr2id;
    $rb1id = $request -> userTeam -> rb1id;
    $rb2id = $request -> userTeam -> rb2id;
    $teid = $request -> userTeam -> teid;
    $pkid = $request -> userTeam -> pkid;
    $defid = $request -> userTeam -> defid;

    // Connect to DB
    $connect = new DBConnection();
    $connect -> connectToDatabase();

    // Call the stored procedure and get the result
    $sql = "CALL setuserlineup('".strval($email)."',".$week.",".$qbid.",".$wr1id.",".$wr2id
            .",".$rb1id.",".$rb2id.",".$teid.",".$pkid.",".$defid.")";
    $result = mysqli_query($connect -> con, $sql);

    // Close SQL Connection
    $connect -> closeConnection();
    
    exit();
}
else
{
    exit();
}

?>