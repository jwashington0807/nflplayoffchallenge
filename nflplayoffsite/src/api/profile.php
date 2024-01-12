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

    $first = validate($request -> user -> first);
    $last = validate($request -> user -> last);
    $email = validate($request -> user -> email);
    $team = validate($request -> user -> team);

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
        $profilesql = "CALL getprofiledata('".strval($first)."' , '".strval($last)."' , '".strval($email)."' , '".strval($team)."')";

        if($result = mysqli_query($connect -> con, $profilesql)) {

            while($row = $result->fetch_assoc()) {
                $myArray[] = $row;
            }
            echo json_encode($myArray);
        }
    }
}
else
{
    exit();
}

?>