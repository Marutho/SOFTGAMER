<?php


    require_once("db.php");
    require_once("Verify.php");
    //extension que econtre que te mete todo en black mode por asi decirlo
    echo '<link type="text/css" id="dark-mode" rel="stylesheet" href="chrome-extension://jabpfojepndedlelamfloejfoopkogcf/data/content_script/general/dark_1.css">';


$nickname = '';
$pass = '';
$mail = '';

if(isset($_POST['nickname']))
{
    $nickname = $_POST['nickname'];
}
if(isset($_POST['pass']))
{
    $pass = $_POST['pass'];
}
if(isset($_POST['mail']))
{
    $mail = $_POST['mail'];
}

if($nickname!='' && $pass!='' && $mail!='')
{
    $query1="SELECT * FROM customer WHERE mail='{$mail}'";
    $resulte = $mysqli->query($query1);

    $query2="SELECT * FROM customer WHERE nickname='{$nickname}'";
    $resultq = $mysqli->query($query2);



    if($resulte->num_rows == 0 && $resultq->num_rows == 0 )
    {      

        echo "Please {$nickname}, we will send a mail confirmation sent to {$mail}, u should activate it soon!<br> <a href='/PAPI/SOFTGAMER/Login.php'> LOGIN </a>";
        $salt = generateRandomString(22);
        $opciones = [
            'salt' => $salt,
        ];
        $pass = password_hash($pass, PASSWORD_DEFAULT, $opciones); 
        $secretCode = generateRandomString();
        
        //var_dump($pass);
        //var_dump($salt);

        $query = "INSERT INTO customer (nickname, pass, mail, active_account, time_span, secret_code, salt, name,subname, Direction, Creditcard, Paypal, Recover_Code) VALUES ('{$nickname}', '{$pass}','{$mail}','false', 0,'{$secretCode}','{$salt}', 'nulo', 'nulo', 'nulo','nulo','nulo','nulo')";
        $result = $mysqli->query($query);
    
        
        
            if(!$result) {
                var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                die($mysqli->error);
    
            }
            sendMailConfirmation($nickname, $secretCode, $mail);
    
            //echo "<p>".$mysqli->insert_id."</p>";
           // echo "<p>".$mysqli->affected_rows."</p>";   
       
    }
    else{

        echo "        
        <p>Ya hay un usuario registrado con este Mail o nickname, prueba a iniciar sesión: </p>
        <a href='/PAPI/SOFTGAMER/Login.php'> LOGIN </a>";
    }   
}
else{
    echo "
    <html>
        <head>
            <title>REGISTER</title>
        </head>
        <body>
        <h1> NO ACCOUNT YET? NO WORRIES </h1>
            <form method ='post' action =''>
                <p><label> Mail: </label>
                <input type='text' name='mail'></p>
                <p><label> Nickname: </label>
                <input type='text' name='nickname'></p>
                <p><label> Password: </label>
                <input type='password' name='pass'></p>
                <input type='submit' value='Create new account'>
                <br>
                <br>
                <a href='https://apijjimenez.000webhostapp.com/PAPI/SOFTGAMER/Login.php'>Return to LOGIN </a>
            </form>
        </body>
    </html>";
}

//GENERATE RANDOM codes for mail verification
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