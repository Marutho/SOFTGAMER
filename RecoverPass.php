<?php

//Import PHPMailer classes into the global namespace
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    require_once("db.php");


    
    
    function sendMailReccover($recoverCode, $customerMail)
    {
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;
        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Username = '';
        //Password to use for SMTP authentication
        $mail->Password = '';
        //Set who the message is to be sent from
        $mail->setFrom('from@example.com', 'Toby Soft');
        //Set an alternative reply-to address
        $mail->addReplyTo('replyto@example.com', 'First Last');
        //Set who the message is to be sent to
        $mail->addAddress($customerMail, $customerMail); //nickname
        //Set the subject line
        $mail->Subject = 'Recover PASSWORD SOFTGAMER Page';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $txtHTML='
            <h1>Time to recover ur password BRO !</h1>  
            <p>
                <a class="boton_source" href="https://apijjimenez.000webhostapp.com/PAPI/SOFTGAMER/RecoverPass.php?mail='.$customerMail.'&recoverCode='.$recoverCode.'&mode=changePassword">CONFIRM BRO</a> 
                <br>

            </p>';

        $mail->msgHTML($txtHTML);
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        //send the message, check for errors
        if (!$mail->send()) {
            echo 'Mailer Error: '. $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
}



if(isset($_GET["mode"]) && isset($_GET["recoverCode"]) )
{
    if($_GET["mode"]=='changePassword')
    {
        
            $newpass='';
            if(isset($_POST['newpass']))
            {
                $newpass = $_POST['newpass'];
            }
            echo "
            <html>
                <head>
                    <title>RecoverPass</title>
                </head>
                <body>
                <h1> ENTER YOUR NEW PASSWORD </h1>
                    <form method ='post' action =''>
                        <p><label> Write here your new passwod</label>
                        <input type='text' name='newpass'></p>
                        <input type='submit' value='Confirm new pass'>
                        
                        
                    </form>
                </body>
            </html>";

            if($newpass!='')
            {
                $salt = generateRandomString(22);
                $opciones = [
                    'salt' => $salt,
                ];
                $passHashed = password_hash($newpass, PASSWORD_DEFAULT, $opciones); 
                $queryPassHashed = "UPDATE customer SET pass ='{$passHashed}', salt = '{$salt}' WHERE Recover_Code='{$_GET["recoverCode"]}'";
                $resulto = $mysqli->query($queryPassHashed);

                if(!$resulto)
                    die($mysqli->error);
                echo " You have now a new Password! Click here, to login now: <a href='https://apijjimenez.000webhostapp.com/PAPI/SOFTGAMER/Login.php'>LOGIN </a>";

            }


    }
}
//<a href='/PAPI/SOFTGAMER/Login.php'>Return to LOGIN </a>
//GENERATE RANDOM codes for account Recover
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}   


?>