<?php

//Import PHPMailer classes into the global namespace
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    require_once("db.php");


function sendMailConfirmation($nickname, $secretCode, $customerMail)
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

    //I had errors, before put this code, now all works!
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
    $mail->addAddress($customerMail, $nickname); //nickname
    //Set the subject line
    $mail->Subject = 'Mail confirmation SOFTGAMER';
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $txtHTML='
        <h1>Welcome to SOFTGAMER '.$nickname.'!</h1>  
        <p>
            <a class="boton_source" href="https://apijjimenez.000webhostapp.com/PAPI/SOFTGAMER/Verify.php?mail='.$customerMail.'&secretcode='.$secretCode.'&mode=true">CONFIRM BRO</a> 
            <br>
            <br>
            <br>
            <img align="center" alt="" src="https://ci6.googleusercontent.com/proxy/flEisl0-m4K5GUmh3wmhAzZTAndB0XG6I_9GegngC35XH4mjFiAChpu6JggzyN6W9nenKRw5b_4UtCvvEmz3jHVsJgEwu5fYNHcKE5cv1wQPLHs_gbi-lDCdViP-XWvubI9jiQIl6oHbhqwV0VuZk7lu6kb_8BahWlA=s0-d-e1-ft#https://gallery.mailchimp.com/2d81d690a922fdf0d966f0961/images/e191d103-1ae4-4132-af8b-0d53b48f1a79.png" width="541.4399999999999" style="max-width:607px;padding-bottom:0;display:inline!important;vertical-align:bottom;border:0;height:auto;outline:none;text-decoration:none"  tabindex="0">

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

if(isset($_GET["mode"]) )
{
    if($_GET["mode"]=='true')
    {
        $queryActivate = "UPDATE customer SET Active_Account ='true' WHERE Secret_Code ='{$_GET["secretcode"]}'";
        $result = $mysqli->query($queryActivate);

        if(!$result)
            die($mysqli->error);
        echo 'Nice, you have now a verfied account, go to <a href="https://apijjimenez.000webhostapp.com/PAPI/SOFTGAMER/Login.php">LOGIN </a>^__^ ';

    }
}

?>