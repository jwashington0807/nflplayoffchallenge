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

    $first = validate($request -> registerdata -> first);
    $last = validate($request -> registerdata -> last);
    $email = validate($request -> registerdata -> email);
    $team = validate($request -> registerdata -> team);
    $password = password_hash($request -> registerdata -> password, PASSWORD_DEFAULT);

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
        $emailsql = "SELECT checkemail('".strval($email)."')";
        $emailresult = mysqli_query($connect -> con, $emailsql);
        $result = mysqli_fetch_row($emailresult);

        if($result[0] == 0)
        {
            // Call the stored procedure and get the result
            $sql = "CALL addnewuser('".strval($first)."' , '".strval($last)."' , '".strval($email)."' , '".strval($password)."' , '".strval($team)."')";
            $result = mysqli_query($connect -> con, $sql);

            echo json_encode($user);
        }
        else {

            $user -> error = "Email already exists";

            // Return JSON back to client
            echo json_encode($user);
        }
    }
}
else
{
    exit();
}

?>