<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('./secure/dbconnection.php');

if($_GET["week"]) 
{
    function validate($data){

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $week = validate($_GET["week"]);

    if (empty($week)) {
        exit();
    }
    else
    {
        // Connect to DB
        $connect = new DBConnection();
        $connect -> connectToDatabase();

        // Check to see if email is available
        $sql = "SELECT getweekeligible(".$week.")";
        $response = mysqli_query($connect -> con, $sql);
        $result = mysqli_fetch_row($response);

        echo json_encode($result);
    }
}
else
{
    exit();
}

?>