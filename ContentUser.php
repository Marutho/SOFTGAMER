<?php

class Content2{

    function __construct()
    {            
        
    }

    function getHTMLUSER($mysqly)
    { 
      
        $content ='';
        $content.='
        <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        </head>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <h1> ☕ Welcome to SOFTGAMER the page for soft gamers ☕  <a href="?cart"><img src="https://images.vexels.com/media/users/3/136826/isolated/preview/4751bdd75f3a5d3616fc98a8cc4dfa4a-icono-de-carrito-de-compras-by-vexels.png" href="?cart" width=100 height=100  alt="errorNoPic""></a></h1>

        <form method ="post" action ="">
                <p><label></label>
                <input type="text" name="producto"></p>
                <input type="submit" name="buscar" value="Search by terms">
                

        </form>           
       

        <div class="row" style="background: grey">
        <div class="col-lg-2" style="background: blue; color: white">
            <ul>
            <li>
            <h1>✨NEW✨</h1>
                <div>
                    <a href="?select=new&new=yes">✨ New ou yeah!✨</a><br>
                    <a href="?select=new&new=no">🔥 Not new but lit too 🔥</a><br>
                </div>
            </li>
            


            <li >
            <h1>COLLECTION</h1>
                <div>
                    <a href="?select=collection&collection=Evangelion">Evangelion</a><br>
                    <a href="?select=collection&collection=Dragon Ball">Dragon Ball</a><br>
                    <a href="?select=collection&collection=90S">90S</a><br>
                    <a href="?select=collection&collection=Cats">Cats</a><br>
                    <a href="?select=collection&collection=Akira">Akira</a><br>
                    <a href="?select=collection&collection=Vintage">Vintage</a><br>
                </div>
            </li>
            
            <li >
            <h1>THEME</h1>
                <div>
                    <a href="?select=theme&theme=Adventure" text-align: right>Adventure</a><br>
                    <a href="?select=theme&theme=Anime">Anime</a><br>                
                    <a href="?select=theme&theme=Aesthetic">Aesthetic</a><br>
                    <a href="?select=theme&theme=Mario Bros">Mario Bros</a><br>                
                    <a href="?select=theme&theme=Casual">Casual</a><br>
                    <a href="?select=theme&theme=Love">Love</a><br>
                    <a href="?select=theme&theme=Classic">Classic</a><br>
                    <a href="?select=theme&theme=Unisex">Unisex</a><br>
                </div>
            </li>
            <li >
            <h1>Tshirt or hoodie?</h1>
                <div class="dropdown-content">
                    <a href="?select=tshirthoodie&tshirthoodie=Yes">Tshirt</a><br>
                    <a href="?select=tshirthoodie&tshirthoodie=No">Hoodie</a><br>
                </div>
            </li>
            </ul>
        </div>
       

             

                <br>
                <br>
                <br>
                <br>
                <br>
                <br>

            

                
        ';
     
            if(isset($_GET['producto']) || isset($_POST['producto']) )
            {
                if(isset($_GET['pageno']))
                {
                    $pageno = $_GET['pageno'];
                }else{
                    $pageno = 1;
                }

                $no_of_images_per_page = 10;
                $offset = ($pageno-1) * $no_of_images_per_page; 

                if(isset($_POST['producto']))
                {
                    $productToSearch=$_POST['producto'];
                }else  
                {
                    $productToSearch=$_GET['producto'];
                }
                
                //var_dump($productToSearch);

                //Para la busqueda del producto por nombre literal
                $searchAnyTerm = '%'.$productToSearch.'%';
                $queryItemsSearchedForName = "SELECT * FROM tshirt WHERE Name LIKE '{$searchAnyTerm}'"; 
                $resultItemsSearchedForName = $mysqly->query($queryItemsSearchedForName);
                //var_dump($resultItemsSearchedForName );

                //PAGINATION
                $total_rows = $resultItemsSearchedForName->num_rows;               
                $total_pages = ceil($total_rows / $no_of_images_per_page);
                //var_dump($total_pages);

                //SACAR LAS CAMISETAS DE CADA PAGINA 
               // var_dump($offset);
               //var_dump($no_of_images_per_page);
                $queryItemsForPage = "SELECT * FROM tshirt  WHERE Name LIKE '{$searchAnyTerm}' LIMIT ".$offset.",".$no_of_images_per_page." ";
                $resultItemsForPage = $mysqly->query($queryItemsForPage);


                if(!$resultItemsForPage) {
                    //var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                    die($mysqly->error);
                }

                $content.= '<div class="col-lg-8">
                 <h1 class="text-center">TSHIRTS</h1>
                 <hr>
                 <div class="card-columns">';


                foreach($resultItemsForPage as $item)
                {

                   //Imprimos Imagen
                   $queryImprimirImagen = "SELECT URL FROM image WHERE ID_PRODUCT = '{$item["ID"]}' ";
                   $resulteImprimirImagen  = $mysqly->query($queryImprimirImagen);
                   
                   if(!$resulteImprimirImagen) {
                        var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                        die($mysqly->error);
                    }

                   $valueImg =  $resulteImprimirImagen->fetch_array(MYSQLI_ASSOC);
                   $imageURL = implode($valueImg); //explota el array a string
                   $content .= '<a href="?veritemhd='.$item["ID"].'"><div class="card"><img src='.$imageURL.' alt="errorNoPic" class="card-img-top""></a>';
                    
                   $content .='
                
                   <div class="card-body">
                        <h5 class="card-title">|'.$item["Name"].'|  |'.$item["Price"].'$ |  |Units: '.$item["Stock"].'|</h5>
                        <p class="card-text">'.$item["Description"].'</p> </div></div>';
                }
                
               
                $content.='<div class="col-lg-2">';
                if($pageno>1) //SI LA PAGINA NO ES LA INICIAL
                {
                    if($pageno >= $total_pages)//SI LA PAGINA ES LA FINAL O MAYOR
                    {
                        $content .= ' <ul>
                            <a href="?producto='.$_GET['producto'].'&pageno='.(intval($pageno)-1).'"><</a>
                        <a href="?producto='.$_GET['producto'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>';
                    }
                    else{   //SI LA PAGINA ES LA ITERMEDIA

                        $content .= ' <ul>           
                        <a href="?producto='.$_GET['producto'].'&pageno='.(intval($pageno)-1).'"><</a>
                        <a href="?producto='.$_GET['producto'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                        <a href="?producto='.$_GET['producto'].'&pageno='.(intval($pageno)+1).'">></a>';
                    }
                }else //SI LA PAGINA ES LA INICIAL
                {
                    $content .= ' <ul>';
                    if(isset($_POST['producto']) && ($pageno != $total_pages))
                    {
                        $content .= '<a href="?producto='.$_POST['producto'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                        <a href="?producto='.$_POST['producto'].'&pageno='.(intval($pageno)+1).'">></a>';
                    }else if(isset($_GET['producto']) && ($pageno != $total_pages)){
                        $content .= '<a href="?producto='.$_GET['producto'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                        <a href="?producto='.$_GET['producto'].'&pageno='.(intval($pageno)+1).'">></a>';
                    }

                }
                $content.='</div>';
            }
            else if (isset($_GET['select']) || isset($_GET['new'] )|| isset($_GET['theme']) || isset($_GET['tshirthoodie']) || isset($_GET['collection']) && !isset($_POST['producto']) ) 
            {
                if(isset($_GET['pageno']))
                {
                    $pageno = $_GET['pageno'];
                }else{
                    $pageno = 1;
                }

                $no_of_images_per_page = 10;
                $offset = ($pageno-1) * $no_of_images_per_page; 
                //$productToSearch=$_GET['producto'];
                //var_dump($productToSearch);

                //Para la busqueda del producto por categoría

                if(isset($_GET['new']))
                {
                    
                    $queryItemsSearchedForName = "SELECT * FROM tshirt WHERE IsNew = '{$_GET['new']}' "; 
                    $resultItemsSearchedForName = $mysqly->query($queryItemsSearchedForName);

                    if(!$resultItemsSearchedForName ) {
                        var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                        die($mysqly->error);
                    }
    
                    //PAGINATION
                    $total_rows = $resultItemsSearchedForName->num_rows;               
                    $total_pages = ceil($total_rows / $no_of_images_per_page);
                    //var_dump($total_pages);

                    $queryItemsForPage= "SELECT * FROM tshirt WHERE IsNew = '{$_GET['new']}'LIMIT ".$offset.",".$no_of_images_per_page." "; 
                    $resultItemsForPage = $mysqly->query($queryItemsForPage);


                }else if(isset($_GET['collection']))
                {

                    $queryItemsSearchedForName = "SELECT * FROM tshirt WHERE Collection  = '{$_GET['collection']}'"; 
                    $resultItemsSearchedForName = $mysqly->query($queryItemsSearchedForName);

                    if(!$resultItemsSearchedForName ) {
                        var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                        die($mysqly->error);
                    }
    
                    //PAGINATION
                    $total_rows = $resultItemsSearchedForName->num_rows;  
                    $total_pages = ceil($total_rows / $no_of_images_per_page) ;            

                    $queryItemsForPage = "SELECT * FROM tshirt WHERE Collection = '{$_GET['collection']}' LIMIT ".$offset.",".$no_of_images_per_page." "; 
                    $resultItemsForPage = $mysqly->query($queryItemsForPage);

                }else if(isset($_GET['theme']))
                {
                    $queryItemsSearchedForName = "SELECT * FROM tshirt WHERE Theme = '{$_GET['theme']}'"; 
                    $resultItemsSearchedForName = $mysqly->query($queryItemsSearchedForName);

                    if(!$resultItemsSearchedForName ) {
                        var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                        die($mysqly->error);
                    }
    
                    //PAGINATION
                    $total_rows = $resultItemsSearchedForName->num_rows;               
                    $total_pages = ceil($total_rows / $no_of_images_per_page);

                    $queryItemsForPage = "SELECT * FROM tshirt WHERE Theme = '{$_GET['theme']}'LIMIT ".$offset.",".$no_of_images_per_page." "; 
                    $resultItemsForPage = $mysqly->query($queryItemsForPage);
                }
                else if(isset($_GET['tshirthoodie']))
                {
                    $queryItemsSearchedForName = "SELECT * FROM tshirt WHERE IsHoodie = '{$_GET['tshirthoodie']}'"; 
                    $resultItemsSearchedForName = $mysqly->query($queryItemsSearchedForName);

                    if(!$resultItemsSearchedForName ) {
                        var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                        die($mysqly->error);
                    }
    
                    //PAGINATION
                    $total_rows = $resultItemsSearchedForName->num_rows;               
                    $total_pages = ceil($total_rows / $no_of_images_per_page);

                    $queryItemsForPage = "SELECT * FROM tshirt WHERE IsHoodie = '{$_GET['tshirthoodie']}'LIMIT ".$offset.",".$no_of_images_per_page." "; 
                    $resultItemsForPage = $mysqly->query($queryItemsForPage);
                }

                $content.= '<div class="col-lg-8">
                 <h1 class="text-center">TSHIRTS</h1>
                 <hr>
                 <div class="card-columns">';


                foreach($resultItemsForPage as $item)
                {

                   //Imprimos Imagen
                   $queryImprimirImagen = "SELECT URL FROM image WHERE ID_PRODUCT = '{$item["ID"]}' ";
                   $resulteImprimirImagen  = $mysqly->query($queryImprimirImagen);
                   
                   if(!$resulteImprimirImagen) {
                        var_dump("voy a morir nooooooooooOOOOOOOOOOOOO D:");
                        die($mysqly->error);
                    }

                   $valueImg =  $resulteImprimirImagen->fetch_array(MYSQLI_ASSOC);
                   $imageURL = implode($valueImg); //explota el array a string
                   $content .= '<div class="card"><a href="?veritemhd='.$item["ID"].'"><img src='.$imageURL.' alt="errorNoPic" class="card-img-top""></a>';
                    
                   $content .='
                
                   <div class="card-body">
                        <h5 class="card-title">|'.$item["Name"].'|  |'.$item["Price"].'$ |  |Units: '.$item["Stock"].'|</h5>
                        <p class="card-text">'.$item["Description"].'</p> </div></div>';
                }

                
                $content.='<div class="col-lg-2">';
                if($pageno>1) //SI LA PAGINA NO ES LA INICIAL
                {
                    if($pageno >= $total_pages)//SI LA PAGINA ES LA FINAL O MAYOR
                    {
                       
                        if (isset($_GET['new']))
                        {
                            $content .= ' <ul>
                            <a href="?new='.$_GET['new'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?new='.$_GET['new'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>';
                        }else if(isset($_GET['tshirthoodie'])){
                            $content .= ' <ul>
                            <a href="?tshirthoodie='.$_GET['tshirthoodie'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?tshirthoodie='.$_GET['tshirthoodie'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>';
                        }else if(isset($_GET['theme'])){
                            $content .= ' <ul>
                            <a href="?theme='.$_GET['theme'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?theme='.$_GET['theme'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>';
                        }else if(isset($_GET['collection'])){
                            $content .= ' <ul>
                            <a href="?collection='.$_GET['collection'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?collection='.$_GET['collection'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>';
                        }
                        
                    }
                    else{   //SI LA PAGINA ES LA ITERMEDIA

                        if (isset($_GET['new']))
                        {
                            $content .= ' <ul>           
                            <a href="?new='.$_GET['new'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?new='.$_GET['new'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?new='.$_GET['new'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }else if(isset($_GET['tshirthoodie'])){
                            $content .= ' <ul>           
                            <a href="?tshirthoodie='.$_GET['tshirthoodie'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?tshirthoodie='.$_GET['tshirthoodie'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?tshirthoodie='.$_GET['tshirthoodie'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }else if(isset($_GET['theme']) ){
                            $content .= ' <ul>           
                            <a href="?theme='.$_GET['theme'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?theme='.$_GET['theme'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?theme='.$_GET['theme'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }else if(isset($_GET['collection'])  ){
                            $content .= ' <ul>           
                            <a href="?collection='.$_GET['collection'].'&pageno='.(intval($pageno)-1).'"><</a>
                            <a href="?collection='.$_GET['collection'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?collection='.$_GET['collection'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }
                       
                    }
                }else //SI LA PAGINA ES LA INICIAL
                {
                    if (isset($_GET['new']) && ($pageno != $total_pages))
                        {
                            $content .= '<a href="?new='.$_GET['new'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?new='.$_GET['new'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }else if(isset($_GET['tshirthoodie'])&& ($pageno != $total_pages)){
                            $content .= '<a href="?tshirthoodie='.$_GET['tshirthoodie'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?tshirthoodie='.$_GET['tshirthoodie'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }else if(isset($_GET['theme'])&& ($pageno != $total_pages)){
                            $content .= '<a href="?theme='.$_GET['theme'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?theme='.$_GET['theme'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }else if(isset($_GET['collection'])&& ($pageno != $total_pages)){
                            $content .= '<a href="?collection='.$_GET['collection'].'&pageno='.intval($pageno).'">'.(intval($pageno)).'</a>
                            <a href="?collection='.$_GET['collection'].'&pageno='.(intval($pageno)+1).'">></a>';
                        }
      
                  
                }
                $content.='</div>';


            }else if(isset($_GET['veritemhd']))
            {
                //imagen
                $queryItem = "SELECT URL FROM image WHERE ID_PRODUCT = '{$_GET['veritemhd']}' ";
                $resultItem  = $mysqly->query($queryItem);
                $valueImg =  $resultItem ->fetch_array(MYSQLI_ASSOC);
                $imageURL = implode($valueImg);

                //Producto
                $queryItem2 = "SELECT * FROM tshirt WHERE ID = '{$_GET['veritemhd']}' ";
                $resultItem2  = $mysqly->query($queryItem2);
                //$valueFeatures = $resultItem2 ->fetch_array(MYSQLI_ASSOC);
                var_dump($resultItem);

                $content.= '<div class="col-lg-8">
                <h1 class="text-center">TSHIRTS</h1>
                <hr>
                <div class="card-columns">';

                foreach($resultItem2 as $item)
                {
                    $content .= '<div class="card"><img src='.$imageURL .' alt="errorNoPic" class="card-img-top""></a>';
                        
                    $content .='
                    
                    <div class="card-body">
                            <h5 class="card-title">|'.$item["Name"].'|  |'.$item["Price"].'$ |  |Units: '.$item["Stock"].'|</h5>
                            <p class="card-text">'.$item["Description"].'</p>
                            <p class="card-text">Is a hoodie? '.$item["IsHoodie"].'</p>
                            <p class="card-text">Collection: '.$item["Collection"].'</p>
                            <p class="card-text">Theme: '.$item["Theme"].'</p> </div></div>
                            <form method ="post" action ="">
                            <p><label></label>
                            <input type="submit" name="Buy" value="Add to cart BRO!">
                            </form> 
                            
                            ';
                            
                }
            }
            if(isset($_POST["Buy"]))
            {
                if($item["Stock"]>=1)
                {
                    $content.= '✔️Nice U add ONE unit of this AWESOME CLOTH to the cart 😎👌✔️';
                    $timeAdded = time();
                    var_dump($timeAdded);
                }else{
                    $content.= '🤔OUT OF STOCK BRO...COME LATER 🤔';
                }
            }
    
            $content.='
            
            

            <a href="/PAPI/SOFTGAMER/Login.php?mode=Logout">Click here to logout </a> </div> </div>';
        return $content;
    }   
}
?>