<?php
 
    session_start();
    require_once("db.php");
    require_once("RecoverPass.php");
    require_once("ContentAdmin.php");
    require_once("ContentUser.php");
    require_once("Utils.php");

    
    $content = new Content();
    $content2 = new Content2();
    
    $nickname='';
    $mailRecover='';

    if(isset($_POST['mailRecover']))
    {
        $mailRecover = $_POST['mailRecover'];
    }
    
    
//extension que econtre que te mete todo en black mode por asi decirlo
echo '<link type="text/css" id="dark-mode" rel="stylesheet" href="chrome-extension://jabpfojepndedlelamfloejfoopkogcf/data/content_script/general/dark_1.css">';
//CONTROL DE SESIONES cargamos gracias a esto datos archivados




if(isset($_GET["mode"]) && ($_GET["mode"])=='Logout' )
    {
        logout();
    }
elseif(isset($_SESSION['login'])){
   if($_SESSION['login'] == 1)
   {    
       if($_SESSION['nickname'] == 'admin') 
       {
            echo $content->getHTMLAdmin($mysqli);
       }
       else{
            echo $content2->getHTMLUSER($mysqli);
       }
       
   }
    else{
        $cookie = $_COOKIE[session_name()];
        echo "<p> Your cookie says {$cookie}</p>";
        var_dump($_SESSION);
    } 
}
else if(isset($_POST["nickname"]) && isset($_POST["password"])  ){
    $nickname = mysql_fix_string($mysqli, $_POST["nickname"]); 
    $password = mysql_fix_string($mysqli, $_POST["password"]);

    //MIRAMOS SI ES EL ADMIN

    if($nickname!='admin')
    {
        //Obtenemos el salt, para nuestro usuario
        $querySelect = "SELECT salt FROM customer WHERE nickname = '{$nickname}'limit 1";
        $resulte = $mysqli->query($querySelect);

        $value =  $resulte->fetch_array(MYSQLI_ASSOC);
        $salt = implode($value); //explota el array, se convierte en string, ya podemos usarlo
        var_dump($value);
        
        //en opciones metemos la semilla
        $opciones = [
            'salt' => $salt,
        ];
        $password = password_hash($password, PASSWORD_DEFAULT, $opciones);  
    }
    

    $stmt= $mysqli->prepare("SELECT * FROM customer WHERE nickname=? AND pass=?");
    

    $stmt->bind_param("ss", $nickname,$password);
    $stmt->execute();
    $result = $stmt->get_result();


    if($result && $result->num_rows ==1){
        if($nickname=='admin')
        {
            $_SESSION['login'] = 1;
            $_SESSION['nickname'] = $nickname; //asi guardamos en la cookie el usuario que queremos, luego con select y tal será facil sacar lo demás.
            echo "<p> WELCOME SR {$nickname} TO SOFTGAMER UR VERY NICE NICE WEB,  <a href='/PAPI/SOFTGAMER/Login.php'> CLICK HERE TO CONTROL TO THE PAGE </a>'</p>";  
        }
        else{       
            $_SESSION['login'] = 1;
            $_SESSION['nickname'] = $nickname;       
            echo "<p> WELCOME {$nickname} TO SOFTGAMER,  <a href='/PAPI/SOFTGAMER/Login.php'> CLICK HERE TO GO TO THE PAGE </a>'</p>";
        }
    }
    else{
        echo '<p> invalid password/under no results </p> <a href="/PAPI/SOFTGAMER/Login.php"> Try again </a>';
        echo $mysqli->error;
    }

}
else if(isset($_GET["mode"])!='recover'){
    echo '
    <html>
    
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
            <title>SOFT GAMER </title>
        </head>
        <body>
        


        
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>




        <h1> WELCOME TO SOFTGAMER </h1>
            <form method ="post" action ="">
                <p><label> Nickname: </label>
                <input type="text" name="nickname"></p>
                <p><label> Password: </label>
                <input type="password" name="password"></p>
                <input type="submit" value="Login">
            </form>
            <br><br><br>
            <a href="/PAPI/SOFTGAMER/Register.php">REGISTER</a>
            <br>
            <br>
 
            <a href="/PAPI/SOFTGAMER/Login.php?mode=recover">Have you forgotten your password ?, click here </a>

            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        </body>
    </html>';
}

if(isset($_GET["mode"]))
{
    if($_GET["mode"]=='recover')
    {
        echo "
    <html>
        <head>
            <title>RecoverPass</title>
        </head>
        <body>
        <h1> RECOVE YOUR PASSWORD </h1>
            <form method ='post' action =''>
                <p><label> Put here the mail, to recover the password </label>
                <input type='text' name='mailRecover'></p>
                <input type='submit' value='Send Mail'>
                <br>
                <br>
                <a href='/PAPI/SOFTGAMER/Login.php'>Return to LOGIN </a>
                
            </form>
        </body>
    </html>";
    }

    
}

if($mailRecover!='')
{
    echo "We sent an email to change your password in {$mailRecover}, Check pls!<br> ";
    $recoverCode = generateRandomString();
    $queryNewpass = "UPDATE customer SET Recover_Code ='{$recoverCode}' WHERE mail ='{$mailRecover}'";
    $result = $mysqli->query($queryNewpass);

        if(!$result)
        {
            echo 'Error no mails like this in our database
            <br>
            <a href="/PAPI/SOFTGAMER/Login.php">Return to LOGIN </a>
            ';
        }

        sendMailReccover($recoverCode, $mailRecover);
            
}






//FUNCTIONS AND OTHER THINGS

function logout(){
    setCookie(session_name(),'',time()-100000,'/');
    session_destroy();
    echo "<p> You logged out succesfully! </p>
    <a href='/PAPI/SOFTGAMER/Login.php'>Home </a>";
}

    

?>