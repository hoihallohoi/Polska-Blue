<!DOCTYPE html>
    <?php        
        $link = new mysqli("localhost","root","usbw","polskablue",3307);
        $productnummer = $_GET["productcode"];
        
        $stmt = mysqli_prepare($link, "SELECT productnummer, naam, omschrijving, afmeting_inhoud, prijs, aantal FROM product WHERE productnummer=?");
        $stmt->bind_param('i',$productnummer);                                                                              //Bind productnummer als interger
        $stmt->execute();                                                                                                   //Voert de voel uit
        $stmt->store_result();                                                                                              //Slaat ze op 
        $stmt->bind_result($productnaam, $product_omschrijving, $product_afmeting, $product_prijs, $product_aantal);        //Bind de results aan waarden
           $stmt->fetch($productnaam, $product_omschrijving, $product_afmeting, $product_prijs, $product_aantal);           //Ophaling ter weegeving resultaten
                                                                              
?> 

<html>
    <head>
        <link rel='stylesheet' type='text/css' href='Productoverzicht.css'>                                         
    </head>
       <body>
        <title><?php print($productnaam); ?></title>
            <h1><?php print ($productnaam); ?></h1>
                <p1><?php print ($product_omschrijving); ?></p1> 
                    <p2>Afmetingen<br><?php print($product_afmeting); ?></p2> 
                       <p3>Prijs <?php print ($product_prijs); ?></p3>
                         <p4>In winkelwagentje</p4>
                         <?php                                                                                                                                    //Doorlinking winkelwagentje
                     Print("<a href ='winkelwagen-update.php?productnummer=$productnummer&naam=$productnaam&prijs=$product_prijs&aantal=$product_aantal'>                                              
                           <img src = 'winkelwagentje.jpg'>
                           </a>");
                          ?>
       </body>
</html>