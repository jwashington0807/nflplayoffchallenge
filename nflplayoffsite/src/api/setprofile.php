<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/user.php');

$postdata = file_get_contents('php://input');

if(isset($postdata) && !empty($postdata)) 
{
    $request = json_decode($postdata);

    function validate($data){

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $first = validate($request -> profile -> first);
    $last = validate($request -> profile -> last);
    $email = validate($request -> profile -> email);
    $team = validate($request -> profile -> team);

    if (empty($first) || empty($last)) {
        exit();
    }
    else if(empty($email)){
        exit();
    }
    else
    {
        // Connect to DB
        $connect = new DBConnection();
        $connect -> connectToDatabase();

        // Create a new User Object to return
        $user = new User();

        // Check to see if email is available
        $profilesql = "CALL setprofiledata('".strval($first)."' , '".strval($last)."' , '".strval($email)."' , '".strval($team)."')";

        if($result = mysqli_query($connect -> con, $profilesql)) {

            echo json_encode($result);
        }

        // Close SQL Connection
        $connect -> closeConnection();
    }
}
else
{
    exit();
}

?>