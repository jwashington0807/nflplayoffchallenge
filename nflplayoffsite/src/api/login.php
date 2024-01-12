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

    function validate($data){

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $uemail = validate($request -> email);
    $passwd = $request -> password;

    if (empty($uemail)) {
        exit();
    }
    else if(empty($passwd)){
        exit();
    }
    else
    {
        // Connect to DB
        $connect = new DBConnection();
        $connect -> connectToDatabase();

        // Call the stored procedure and get the result
        $sql = "CALL tryuserlogin('".strval($uemail)."')";
        $rows = mysqli_query($connect -> con, $sql);

        // Get the number of rows returned by query
        $rowcount = mysqli_num_rows($rows);

        // Create a new User Object to return
        $user = new User();

        // Check if we got a match, if not then invalid
        if ($rowcount == 1) {

            while($row = mysqli_fetch_array($rows))
            {
                if(password_verify($passwd, $row['passwd'])) 
                {
                    session_start();

                    // Initialize variables
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['loggedin'] = 1;
                    $_SESSION['token'] = session_id();

                    $user -> first = $row['firstname'];
                    $user -> last = $row['lastname'];
                    $user -> email = $row['email'];
                    $user -> token = session_id();
                }
                else {
                    $user -> error = "Invalid email or password";
                }
            }
        }
        else
        {
            $user -> error = "Invalid email or password";
        }

        // Return JSON back to client
        echo json_encode($user);
    }
}
else
{
    exit();
}

?>