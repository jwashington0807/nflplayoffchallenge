<?php

header("Access-Control-Allow-Origin: http://dev.justmejt.com");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/user.php');

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)) 
{
    $request = json_decode($postdata);

    $email = $request -> data -> email;
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $password = password_hash($request -> data -> password1, PASSWORD_DEFAULT);

    // Create a new User Object to return
    $user = new UserResponse();

    if (!$email) 
    {
        $user -> error  = "Invalid email address";
    }
    else
    {
        // Connect to DB
        $connect = new DBConnection();
        $connect -> connectToDatabase();

        // Reset the user password
        $sql = "CALL resetpassword('".strval($email)."','".strval($password)."')";
        $result = mysqli_query($connect -> con, $sql);

        $user -> email = $email;
    }
    
    // Return JSON back to client
    echo json_encode($user);
}
else
{
    exit();
}

?>