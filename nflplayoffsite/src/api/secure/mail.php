<?php
header("Access-Control-Allow-Origin: http://dev.justmejt.com");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

require("./PHPMailer.php");
require("./SMTP.php");
require("./Exception.php");

if(!empty($_POST))
{
    try 
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        
        // Get Data Passed
        $emailaddress = $_POST['email'];
        $message = $_POST['message'];
        $name = $_POST['name'];
        $subject = "Contact - JustMeJT";

        $mail->IsSMTP();
        $mail->Host = "mail.justmejt.com"; // Enter your host here
        $mail->SMTPAuth = true;
        $mail->Username = "contact@justmejt.com"; // Enter your email here
        $mail->Password = "H@waii88!"; //Enter your password here
        $mail->Port = 587;
        $mail->isHTML(true);                                  
        $mailContent = "From: <strong>" . $emailaddress . "</ br></ br>
        <p>An inquiry was submitted from " . $name . " justmejt.com with the below message: </p><br />
        <p><h4>" . $message . "</h4></p>";

        $mail->Subject = $subject;
        $mail->Body = $mailContent;
        $mail->setFrom('contact@justmejt.com', 'Name');        
        $mail->addAddress('jaytee.washington@gmail.com');
        
    
        $mail->Subject = 'JUSTMEJT Inquiry';
        $mail->Body = $mailContent;

        if(!$mail->send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        else
        {
            echo "<div class='error'>
            <p>An email has been sent to you with instructions on how to reset your password.</p>
            </div><br /><br /><br />";
        }
    } 
    catch (Exception $e) 
    {
        echo $e;
    }
}

?>