<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/user.php');

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)) 
{
    $request = json_decode($postdata);

    // Get email
    $email = $request -> data -> email;
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    // get key
    $key = $request -> data -> key;

    // get current date
    $curDate = date("Y-m-d H:i:s");

    // Create a new User Object to return
    $user = new UserResponse();

    if (!$email || !$key) 
    {
        $user -> error  = "Invalid email address or key";
    }
    else
    {
        // Connect to DB
        $connect = new DBConnection();
        $connect -> connectToDatabase();

        // Check to see if reset entry is valid
        $query = mysqli_query($connect -> con, "SELECT * FROM `temp_reset` WHERE `resetkey`='".$key."' and `email`='".$email."';");
        $row = mysqli_num_rows($query);
        
        if ($row == "")
        {
            $user -> error = 'The link is invalid/expired. Either you did not copy the correct link
            from the email, or you have already used the key in which case it is 
            deactivated';
        } 
        else 
        {
            $row = mysqli_fetch_assoc($query);
            $expDate = $row['expdate'];
            if ($expDate >= $curDate)
            {
                $user -> email  = $email;
            }
            else {
                $user -> error = 'The link is expired. You are trying to use the expired link which 
                as valid only 24 hours (1 days after request).';
            }
        }
    }

    // Return JSON back to client
    echo json_encode($user);
}
else
{
    exit();
}

?>