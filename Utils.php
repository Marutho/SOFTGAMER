<?php

require_once("db.php");

function createAlotOfUsers_Mails($mysqli)
{
    //cargamos un json de nombres ordenados de manera alfabetica
    $url = "https://apijjimenez.000webhostapp.com/PAPI/SOFTGAMER/Users.json";
    $json = file_get_contents($url);
    $array = json_decode($json,TRUE);

        for($i = 0; $i <= 100; $i++)
        {
            //que posicion del arrayy pillara, un nombre
            $randomNickNumber=rand(0,sizeof($array)-1);
            //para que no sean nicks tan planos, añadimos numeros, asi ademas es mas dificil que se repitan
            $randomNumbers=rand(0,100);
            //juntamos nick + num
            $nickname= $array[$randomNickNumber].$randomNumbers;
            //var_dump($nickname);
            //el mail sera lo mismo con testing.com para diferencia de los que cree de manera manual
            $mail= $nickname.'@testing.com';
            //var_dump($mail);
            //generamos la contraseña de manera aleatoria NO AÑADIMOS salt, porque tendriamos que crear otro script si queremos ver la contraseña para ver el perfil del usuario.
            $password=generateRandomPassword();


            //antes comprobemos que no se repite el nickname
            $querySelect2 = "SELECT * FROM customer WHERE nickname = '{$nickname}'";
            $resultq = $mysqli->query($querySelect2);

            if($resultq->num_rows == 0 )
            {
                $queryInsert = "INSERT INTO customer(Mail,Nickname,Pass,Name,Subname,CreditCard,Direction,
                Paypal,Salt,Active_Account, Secret_Code, Recover_Code, Time_Span) VALUES ('{$mail}','{$nickname}','{$password}','none','none',
                'none','none','none','aaaaaaaaaaaaaaaaaaaaaa','true','none', 'none', 0)";
                $result = $mysqli->query($queryInsert);
            }
            else{
                $i=$i-1;
            }
            if(!$result) {
                //var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                die($mysqli->error);
            }

 

        }
    

}

function createAlotOfItems($mysqli)
{
    //cargamos un json de nombres ordenados de manera alfabetica
    $url = "https://apijjimenez.000webhostapp.com/PAPI/SOFTGAMER/Items.json";
    $json = file_get_contents($url);
    $array = json_decode($json,TRUE);

        for($i = 0; $i <= 10; $i++)
        {
            //que posicion del arrayy pillara una url
            $randomURLIMG=rand(0,sizeof($array)-1);
            $URLItem= $array[$randomURLIMG];

            $categoryItem=randomCategory();
            $collectionItem=randomCollection();
            $nameProduct=$categoryItem.' '.randomCollection();
            $price=rand(15,80);
            $stock=rand(1,20);
            $description="Lore ipsum bla bla bla";
            $isHoodie=generateYesNo();
            $isNew= generateYesNo();

            //The t-shirt
            $queryInsert = "INSERT INTO tshirt (Stock,Price,Collection,IsHoodie,IsNew,Theme,Name,Description,Popular) VALUES ('{$stock}','{$price}',
            '{$collectionItem}','{$isHoodie}','{$isNew}','{$categoryItem}','{$nameProduct}','{$description}','no info')";
            $result = $mysqli->query($queryInsert);

            
            if(!$result) {
                //var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                die($mysqli->error);
            }



            //Sacamos la ID de lo último introducido
            $queryll ="SELECT last_insert_id()";
            $r = $mysqli->query($queryll);

            if(!$r) {
                //var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                die($mysqli->error);
            }
            $valueID =  $r->fetch_array(MYSQLI_ASSOC);
            $IDproduct = implode($valueID); //explota el array a string
            $IDproduct = intval($IDproduct);
            var_dump($IDproduct);

             //query para finalmente añadir la foreign key el idproduct obtenido
             $query2=  "INSERT INTO image (URL, ID_Product) VALUES ('{$URLItem}','{$IDproduct}')";
             $result2 = $mysqli->query($query2);



 

        }
}



//script que hemos usado antes, deberia de hacer que todos usaran este script y ya
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}  

function generateYesNo(){
    $randomNumber=rand(0,1);
    if($randomNumber==1){
        return "Yes";
    }
    else{
        return "No";
    }
}

function randomCategory()
{
    $array= array("Adventure", "Anime", "Aesthetic", "Mario Bros", "Casual", "Love", "Classic", "Unisex");
    $randomNumber=rand(0,7);
    return $array[$randomNumber];
}

function randomCollection()
{
    $array= array("Evangelion", "Dragon Ball", "90S", "Cats", "Akira", "Vintage");
    $randomNumber=rand(0,5);
    return $array[$randomNumber];
}




?>