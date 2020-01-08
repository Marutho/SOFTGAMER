<?php

    class Content{

        function __construct()
        {            
            
        }

        function getHTMLAdmin($mysqly)
        {   
            $nameProduct ='';
            $descriptionProduct ='';
            $themeProduct ='';
            $stockProduct ='';
            $priceProduct ='';
            $collectionProduct ='';
            $isNewProduct ='';
            $isHoodieProduct ='';
            $imageProduct ='';
        
            if(isset($_POST['nameProduct']))
            {
                $nameProduct  = $_POST['nameProduct'];
            }
            if(isset($_POST['description']))
            {
                $descriptionProduct = $_POST['description'];
            }
            if(isset($_POST['collection']))
            {
                $collectionProduct = $_POST['collection'];
            }
        
            if(isset($_POST['stock']))
            {
                $stockProduct = $_POST['stock'];
            }
        
            if(isset($_POST['price']))
            {
                $priceProduct = $_POST['price'];
            }
        
            if(isset($_POST['isHoodie']))
            {
                $isHoodieProduct = $_POST['isHoodie'];
            }
        
            if(isset($_POST['isNew']))
            {
                $isNewProduct = $_POST['isNew'];
            }
        
            if(isset($_POST['theme']))
            {
                $themeProduct= $_POST['theme'];
            }
            if(isset($_POST['imageProduct']))
            {
                $imageProduct= $_POST['imageProduct'];
            }

            $content = "";
            if(isset($_GET["manage"]) && $_GET["manage"] =='item')
            {
                
                if(isset($_GET["option"]) && $_GET["option"] =='upload')
                {
                    if($nameProduct!='' && $descriptionProduct!='' && $collectionProduct!=''
                    && $collectionProduct!='' && $stockProduct !=''&& $priceProduct!='' && $isHoodieProduct!=''
                    && $isHoodieProduct!='' && $isNewProduct!=''&& $themeProduct!='' && $imageProduct!='')
                    {   
                        $querySelect2 = "SELECT * FROM tshirt WHERE name ='{$nameProduct}'";
                        $resultq = $mysqly->query($querySelect2);
                        
                        if($resultq->num_rows == 0 )
                        {
                            $query1 = "INSERT INTO tshirt (stock, price, collection, isHoodie, isNew, theme, name, description) VALUES ('{$stockProduct}'
                            , '{$priceProduct}','{$collectionProduct}','{$isHoodieProduct}','{$isNewProduct}','{$themeProduct}','{$nameProduct}',
                            '{$descriptionProduct}')";
                            $result1 = $mysqly->query($query1);
                            var_dump($result1);
                            
                            
                            if(!$result1) {
                                var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                die($mysqly->error);
                            }
                            
                            //query para extraer el id asociado al nombre irrepetible
                            //Obtenemos el ID, para asociarlo, como no lo escribimos manual tendremos que saber por donde va el autoincrement.
                            $queryID = "SELECT id FROM tshirt WHERE name = '{$nameProduct}'limit 1";
                            $resulteID = $mysqly->query($queryID);
                            $valueID =  $resulteID->fetch_array(MYSQLI_ASSOC);
                            $IDproduct = implode($valueID); //explota el array a string
                            $IDproduct = intval($IDproduct); //pasamos de string a number con intvalue
                            //var_dump($IDproduct);


                            //query para finalmente añadir la foreign key el idproduct obtenido
                            $query2=  "INSERT INTO image (URL, ID_Product) VALUES ('{$imageProduct}','{$IDproduct}')";
                            $result2 = $mysqly->query($query2);
    
                            if(!$result2) {
                                var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                die($mysqly->error);
                            }
    
                            $content .=" <h1>PRODUCT UPLOADED!
                            <br><br><br>
                            <a href='/PAPI/SOFTGAMER/Login.php'> Return to management menu </a> ";
                        }else
                        {
                            $content .=" <h1>NOT UPLOADED! THERE IS ONE PRODUCT WITH THE SAME NAME ALREADY
                            <br><br><br>
                            <a href='/PAPI/SOFTGAMER/Login.php'> Return to management menu </a> ";
                        }
                        
                            
                    }
                    else{
                        $content .= "<h1>DETAILS OF THE NEW PRODUCT</h1>
                    
                        <form method ='post' action =''>
                        <p><label> Name of the product: </label>
                        <input type='text' name='nameProduct'></p>
                        <p><label> Description: </label>
                        <input type='text' name='description'></p>
                        <p><label> Stock: </label>
                        <input type='text' name='stock'></p>
                        <p><label> The initial price: </label>
                        <input type='text' name='price'></p>
                        <p><label> Collection videogame: </label>
                        <input type='text' name='collection'></p>
                        <p><label> Is a hoodie?: </label>
                        <input type='text' name='isHoodie'></p>
                        <p><label> Is it new? </label>
                        <input type='text' name='isNew'></p>
                        <p><label> Theme </label>
                        <input type='text' name='theme'></p>
                        <p><label> Add an URL image of the new product (YOU MUST!) </label>
                        <input type='text' name='imageProduct'></p>
                        <input type='submit' value='Upload'>
                        
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <a href='/PAPI/SOFTGAMER/Login.php'> Return to management menu </a> 
                        ";
                    }
                    
                }
                else if(isset($_GET["option"]) && $_GET["option"] =='readupdatedelete'){

                    if (isset($_GET["ideditproduct"]))
                    {
                        if(isset($_GET["mode"]) && $_GET["mode"]=='delete')
                        {
                            $queryDelete= "DELETE FROM tshirt WHERE ID='{$_GET["ideditproduct"]}'limit 1";
                            $resultDelete = $mysqly->query($queryDelete);

                            if(!$resultDelete)
                                die($mysqly->error);
                            
                            $content.='<h1>The item #'.$_GET["ideditproduct"].' has been deleted succesfully';

                        }else{
                            $queryProductEdit = "SELECT * FROM tshirt WHERE ID='{$_GET["ideditproduct"]}'limit 1";
                            $resultEditProduct = $mysqly->query($queryProductEdit);
                            $editProduct = $resultEditProduct->fetch_array(MYSQLI_ASSOC);
                            //var_dump($editProduct);
    
                            if(!$resultEditProduct) {
                                var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                die($mysqly->error);
                            }
    
    
                            if(isset($_POST['ModifyItem']) && isset($_GET['ideditproduct']))
                            {
                                
    
                                $queryUpdateItem = "UPDATE tshirt SET 
                                        Name = '{$_POST['nameProduct']}',
                                        Description = '{$_POST['description']}',
                                        Price = '{$_POST['price']}',
                                        Stock = '{$_POST['stock']}',
                                        Collection = '{$_POST['collection']}',
                                        IsHoodie = '{$_POST['isHoodie']}',
                                        IsNew = '{$_POST['isNew']}',
                                        Theme = '{$_POST['theme']}'
                                        WHERE ID = '{$_GET["ideditproduct"]}'";
                                
                                $resultUpItem = $mysqly->query($queryUpdateItem);
                                if(!$resultUpItem) {
                                    var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                    die($mysqly->error);
                                }

                                

                                $queryUpdateItemImage = "UPDATE image  SET URL = '{$_POST['imageProduct']}' WHERE ID_Product ='{$_GET['ideditproduct']}'";
                                $resultUpItemImage = $mysqly->query($queryUpdateItemImage );
                                if(!$resultUpItemImage) {
                                    var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                    die($mysqly->error);
                                }                               
                            }
                            $content .= "<h1>You are editing the product #".$_GET['ideditproduct']."</h1><form method ='post' action =''>
                            <p><label> Name of the product: </label>
                            <input type='text' name='nameProduct'></p>
                            <p><label> Description: </label>
                            <input type='text' name='description'></p>
                            <p><label> Stock: </label>
                            <input type='text' name='stock'></p>
                            <p><label> The initial price: </label>
                            <input type='text' name='price'></p>
                            <p><label> Collection videogame: </label>
                            <input type='text' name='collection'></p>
                            <p><label> Is a hoodie?: </label>
                            <input type='text' name='isHoodie'></p>
                            <p><label> Is it new? </label>
                            <input type='text' name='isNew'></p>
                            <p><label> Theme </label>
                            <input type='text' name='theme'></p>
                            <p><label> Add an URL image of the new product (YOU MUST!) </label>
                            <input type='text' name='imageProduct'></p>";

                            //Imprimos Imagen
                            $queryImprimirImagen = "SELECT URL FROM image WHERE ID_PRODUCT = '{$_GET['ideditproduct']}'limit 1";
                            $resulteImprimirImagen  = $mysqly->query($queryImprimirImagen);
                            $valueImg =  $resulteImprimirImagen->fetch_array(MYSQLI_ASSOC);
                            $imageURL = implode($valueImg); //explota el array a string
                            $content .= '<img src='.$imageURL.' alt="errorNoPic"  height="200" width="150"><br><br>';

                            $content .= '<input type="submit" name="ModifyItem" value="Modify Item">
                                         </form><br><br><a href="/PAPI/SOFTGAMER/Login.php?manage=item&option=readupdatedelete> Return </a>';
                        }
  
                    }
                    else{
                        $queryItems = "SELECT * FROM tshirt";
                        $resultItems = $mysqly->query($queryItems);
                        
                        if(!$resultItems) {
                            var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                            die($mysqly->error);
                        }

                        $content .= "<table>
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Collection</th>
                                            <th>Is a hoodie?</th>
                                            <th>Is it new?</th>
                                            <th>Theme/Genre</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                            ";

                            foreach($resultItems as $item)
                            {
                                $content .='
                                <tr>
                                    <td>'.$item["ID"].'</td>
                                    <td>'.$item["Name"].'</td>
                                    <td>'.$item["Description"].'</td>
                                    <td>'.$item["Stock"].'</td>
                                    <td>'.$item["Price"].'</td>
                                    <td>'.$item["Collection"].'</td>
                                    <td>'.$item["IsHoodie"].'</td>
                                    <td>'.$item["IsNew"].'</td>
                                    <td>'.$item["Theme"].'</td>
                                    <td><a href="/PAPI/SOFTGAMER/Login.php?manage=item&option=readupdatedelete&ideditproduct='.$item["ID"].'">Edit</a></td>
                                    <td><a href="/PAPI/SOFTGAMER/Login.php?manage=item&option=readupdatedelete&ideditproduct='.$item["ID"].'&mode=delete">Delete</a></td>
                                
                                </tr>
                                
                                
                                ';
                            }
                        }
                        $content .='</tbody></table><br> <br> <br><a href="/PAPI/SOFTGAMER/Login.php?manage=item"> Return to management menu </a>';
    
                }
                else if (isset($_GET["option"]) && $_GET["option"] =='godmod'){
                    $content .= '<h1>3 NEW ITEMS CREATED!
                    </tbody></table><br> <br> <br><a href="/PAPI/SOFTGAMER/Login.php?manage=item"> Return to management menu </a>
                    <br>
                    <br>';
                    createAlotOfItems($mysqly);
                }
                else
                {
                    $content .= '<h1>What do u want to do admin?
                    <br>
                    <br>
                    <a href="/PAPI/SOFTGAMER/Login.php?manage=item&option=upload"> Upload new shirt/hoodie </a><br> 
                    <a href="/PAPI/SOFTGAMER/Login.php?manage=item&option=readupdatedelete"> See current uploaded items </a><br> 
                    <a href="/PAPI/SOFTGAMER/Login.php?manage=item&option=godmod"> Create a lot of entries for testing</a><br> <br>
                    <a href="/PAPI/SOFTGAMER/Login.php"> Return to management menu </a> ';
                }

            }else if(isset($_GET["manage"]) && $_GET["manage"] =='order')
            {
                if(isset($_GET["option"]) && $_GET["option"] =='readupdatedelete'){

                    if (isset($_GET["ideorder"]))
                    {
                        if(isset($_GET["mode"]) && $_GET["mode"]=='delete')
                        {
                            $queryDelete= "DELETE FROM c_order WHERE ID='{$_GET["ideorder"]}'limit 1";
                            $resultDelete = $mysqly->query($queryDelete);

                            if(!$resultDelete)
                                die($mysqly->error);
                            
                            $content.='<h1>The order #'.$_GET["ideorder"].' has been deleted succesfully';

                        }
                        else if(isset($_GET["mode"]) && $_GET["mode"]=='seeProducts')
                        {
                            $queryOrders2 = "SELECT * FROM order_a_product WHERE ID_Order='{$_GET["ideorder"]}'";
                            $resultOrders2  = $mysqly->query($queryOrders2 );
                         
                            if(!$resultOrders2 ) {
                                var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                die($mysqly->error);
                            }


                            $queryID = "SELECT ID_Product FROM order_a_product WHERE ID_Order='{$_GET["ideorder"]}' limit 1";
                            $resulteID = $mysqly->query($queryID);
                            $valueID =  $resulteID->fetch_array(MYSQLI_ASSOC);
                            $IDproduct = implode($valueID); //explota el array a string
                            $IDproduct = intval($IDproduct); //pasamos de string a number con intvalue

                            //PARA MOSTRAR EL OBJETO
                            $queryOrders3 = "SELECT * FROM tshirt WHERE ID='{$IDproduct}'"; 
                            $resultOrders3  = $mysqly->query($queryOrders3);
                         
                            if(!$resultOrders3 ) {
                                var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                die($mysqly->error);
                            }
    
    
    
    
                            $content .= "<table>
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>ID Product</th>
                                                <th>ID ORDER</th>
                                                <th>Quantity of that item</th>
                                                <th>Price in that moment</th>
                                                <th>Name of the product</th>
                                                <th>Collection of the product</th>
    
                                            </tr>
                                            </thead>
                                            <tbody>
                                ";
    
                                foreach($resultOrders2 as $item)
                                {
                                    $content .='
                                    <tr>
                                        <td>'.$item["ID"].'</td>
                                        <td>'.$item["ID_Product"].'</td>
                                        <td>'.$item["ID_Order"].'</td>
                                        <td>'.$item["Number_Quantity"].'</td>
                                        <td>'.$item["Price_At_The_Moment"].'</td>

                                    ';               
                                }

                                foreach($resultOrders3 as $item)
                                {
                                    $content .='
                                    
                                        <td>'.$item["Name"].'</td>
                                        <td>'.$item["Collection"].'</td>
                                        </tr>
                                    ';               
                                }
                        }
                        else{
                            $queryOrderEdit = "SELECT * FROM c_order WHERE ID='{$_GET["ideorder"]}'limit 1";
                            $resultEditOrder = $mysqly->query($queryOrderEdit);
                            $editOrder = $resultEditOrder->fetch_array(MYSQLI_ASSOC);
                            //var_dump($editOrder);
    
                            if(!$resultEditOrder) {
                                var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                die($mysqly->error);
                            }
    
    
                            if(isset($_POST['ModifyOrder']) && isset($_GET['ideorder']))
                            {
                                
    
                                $queryUpdateOrder= "UPDATE c_order SET 
                                        Sent = '{$_POST['Sent']}'                                       
                                        WHERE ID = '{$_GET["ideorder"]}'";
                                
                                $resultUpOrder = $mysqly->query($queryUpdateOrder);
                                if(!$resultUpOrder) {
                                    var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                    die($mysqly->error);
                                }


                                
                            }

                            
    
                            $content .= "<h1>You are editing the order #".$_GET['ideorder']."</h1><form method ='post' action =''>

                            <p><label> Is sent?: </label>
                            <input type='text' name='Sent'></p>";
                            
    
                            $content .= '<input type="submit" name="ModifyOrder" value="Modify Order">
                                         </form><br><br><a href="/PAPI/SOFTGAMER/Login.php?manage=order&option=readupdatedelete> Return </a>';
                        }

                        

                        
                    }
                    else{
                        $queryOrders = "SELECT * FROM c_order";
                        $resultOrders  = $mysqly->query($queryOrders );
                        
                    
                        if(!$resultOrders ) {
                            var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                            die($mysqly->error);
                        }



                        $content .= "<table>
                                        <thead>
                                        <tr>
                                            <th>ID Product</th>
                                            <th>ID Costumer</th>
                                            <th>Is Payed?</th>
                                            <th>Sent</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                            ";

                            foreach($resultOrders as $item)
                            {
                                $content .='
                                <tr>
                                    <td>'.$item["ID"].'</td>
                                    <td>'.$item["ID_Costumer"].'</td>
                                    <td>'.$item["IsPayed"].'</td>
                                    <td>'.$item["Sent"].'</td>

                                    <td><a href="/PAPI/SOFTGAMER/Login.php?manage=order&option=readupdatedelete&ideorder='.$item["ID"].'">Edit</a></td>
                                    <td><a href="/PAPI/SOFTGAMER/Login.php?manage=order&option=readupdatedelete&ideorder='.$item["ID"].'&mode=seeProducts">See the products of the order</a></td>
                                    <td><a href="/PAPI/SOFTGAMER/Login.php?manage=order&option=readupdatedelete&ideorder='.$item["ID"].'&mode=delete">Cancel order</a></td>

                                </tr>
                                
                                
                                ';
                            }
                        }
                        $content .='</tbody></table><br> <br> <br><a href="/PAPI/SOFTGAMER/Login.php?manage=order"> Return to management menu </a>';
    
                }
                else
                {
                    $content .= '<h1>What do u want to do admin?
                    <br>
                    <br>
                    <a href="/PAPI/SOFTGAMER/Login.php?manage=order&option=readupdatedelete"> See current orders</a><br> <br>
                    <a href="/PAPI/SOFTGAMER/Login.php"> Return to management menu </a> ';
                }
            }else if(isset($_GET["manage"]) && $_GET["manage"] =='user')
            {

                
                if(isset($_GET["option"]) && $_GET["option"] =='readupdatedelete'){

                    if (isset($_GET["ideuser"]))
                    {
                        if(isset($_GET["mode"]) && $_GET["mode"]=='delete')
                        {
                            $queryDelete= "DELETE FROM customer WHERE ID='{$_GET["ideuser"]}'limit 1";
                            $resultDelete = $mysqly->query($queryDelete);

                            if(!$resultDelete)
                            {
                               var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                                die($mysqly->error); 
                            }
                             
                            
                            $content.='<h1>The user#'.$_GET["ideuser"].' has been deleted succesfully';

                        }                        

                        
                    }
                    else{
                        $queryItems = "SELECT * FROM customer";
                        $resultItems = $mysqly->query($queryItems);
                        
                    
                        if(!$resultItems) {
                            var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                            die($mysqly->error);
                        }



                        $content .= "<table>
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nickname</th>
                                            <th>Mail</th>
                                            <th>Name</th>
                                            <th>Subname</th>                                            
                                            <th>Direction</th>
                                            <th>Creditcard</th>
                                            <th>Paypal</th>
                                            <th>Active Account</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                            ";

                            foreach($resultItems as $item)
                            {
                                $content .='
                                <tr>
                                    <td>'.$item["ID"].'</td>
                                    <td>'.$item["Nickname"].'</td>
                                    <td>'.$item["Mail"].'</td>
                                    <td>'.$item["Name"].'</td>
                                    <td>'.$item["Subname"].'</td>
                                    <td>'.$item["Direction"].'</td>
                                    <td>'.$item["Creditcard"].'</td>
                                    <td>'.$item["Paypal"].'</td>
                                    <td>'.$item["Active_Account"].'</td>
                                    <td><a href="/PAPI/SOFTGAMER/Login.php?manage=user&option=readupdatedelete&ideuser='.$item["ID"].'&mode=delete">Delete</a></td>
                                
                                </tr>
                                
                                
                                ';
                            }
                        }
                        $content .='</tbody></table><br> <br> <br><a href="/PAPI/SOFTGAMER/Login.php?manage=user"> Return to management menu </a>';
    
                }
                else if(isset($_GET["option"]) && $_GET["option"] =='godmod')
                {
                    $content .= '<h1>100 NEW USERS CREATED!
                    </tbody></table><br> <br> <br><a href="/PAPI/SOFTGAMER/Login.php?manage=user"> Return to management menu </a>
                    <br>
                    <br>';
                    createAlotOfUsers_Mails($mysqly);
                }
                else
                {
                    $content .= '<h1>What do u want to do admin?
                    <br>
                    <br>
                    <a href="/PAPI/SOFTGAMER/Login.php?manage=user&option=readupdatedelete"> See list of users </a><br> 
                    <a href="/PAPI/SOFTGAMER/Login.php?manage=user&option=godmod"> Create a lot of entries for testing</a><br> <br>
                    <a href="/PAPI/SOFTGAMER/Login.php"> Return to management menu </a> ';
                }
            }else{
                $content .= '
                <h1>What do u want to do admin?
                <br>
                <a href="/PAPI/SOFTGAMER/Login.php?manage=item"> Manage items </a> or 
                <a href="/PAPI/SOFTGAMER/Login.php?manage=order"> Manage orders </a> or
                <a href="/PAPI/SOFTGAMER/Login.php?manage=user"> Manage users </a>

                <br>
                <br>
                <a href="/PAPI/SOFTGAMER/Login.php?mode=Logout"> Logout </a>';

            }


            return $content;

        }

        function getHTMLCustomer()
        {
            $content = "";
            $content .= 'Encantado soft gamer! Bienvenido a tu página favorita<a href="/PAPI/SOFTGAMER/Login.php?mode=Logout"> Logout </a>';
            return $content;

        }

    }

?>