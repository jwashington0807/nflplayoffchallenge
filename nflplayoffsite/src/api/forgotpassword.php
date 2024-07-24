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

    $email = $request -> forgot -> email;
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

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

        // Check to see if email is available
        $emailsql = "SELECT checkemail('".strval($email)."')";
        $emailresult = mysqli_query($connect -> con, $emailsql);
        $result = mysqli_fetch_row($emailresult);

        if($result[0] == 0)
        {
            $user -> error  = "Invalid email address";
        }
        else {
            $expFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
            $expDate = date("Y-m-d H:i:s", $expFormat);
            $key = md5($email);
            $addKey = substr(md5(uniqid(rand(),1)),3,10);
            $key = $key . $addKey;

            // Encrypt Key and Email

            // Insert Temp Table
            $sql = "CALL tempemail('".strval($email)."' , '".strval($key)."' , '".strval($expDate)."')";
            $result = mysqli_query($connect -> con, $sql);

            // Compose Email
            $output='<p>Please click on the following link to reset your password.</p>';
            $output.='<p>-------------------------------------------------------------</p>';
            $output.='<p><a href="https://nflplayoffs.justmejt.com/reset/'.$key.'/'.$email.'" target="_blank">
            Reset Password</a></p>';	

            $output.='<p>-------------------------------------------------------------</p>';
            $output.='<p>Please be sure to copy the entire link into your browser.
            The link will expire after 1 day for security reason.</p>';
            $output.='<p>If you did not request this forgotten password email, no action 
            is needed, your password will not be reset. However, you may want to log into 
            your account and change your security password as someone may have guessed it.</p>';   	
            $output.='<p>Thanks,</p>';
            $output.='<p>JustMEJT Team</p>';
            $body = $output; 
            $subject = "Password Recovery - NFLPlayoffChallenge";

            // Prepare to Send
            require("./secure/PHPMailer.php");
            require("./secure/SMTP.php");
            require("./secure/Exception.php");

            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->IsSMTP();
            $mail->Host = "mail.justmejt.com"; // Enter your host here
            $mail->SMTPAuth = true;
            $mail->Username = "contact@justmejt.com"; // Enter your email here
            $mail->Password = "H@waii88!"; //Enter your password here
            $mail->Port = 587;
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AddAddress($email);
            $mail->setFrom('contact@justmejt.com');

            if(!$mail->send())
            {
                echo "Mailer Error: " . $mail->ErrorInfo;
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