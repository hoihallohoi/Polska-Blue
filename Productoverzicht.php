<!DOCTYPE html>
    <?php
        
        $link = mysqli_connect("localhost","root","usbw","polskablue",3307);
        $productnummer = $_GET["productcode"];
        $Resultaat_productnummer=      mysqli_query($link, "SELECT productnummer FROM product WHERE productnummer=$productnummer"); 
        $Resultaat_naam         =      mysqli_query($link, "SELECT naam FROM product WHERE productnummer=$productnummer");                //Selectie
        $Resultaat_omschrijving =      mysqli_query($link, "SELECT omschrijving FROM product WHERE productnummer=$productnummer");
        $Resultaat_afmetingen   =      mysqli_query($link, "SELECT afmetingen_inhoud FROM product WHERE productnummer=$productnummer"); 
        $Resultaat_prijs        =      mysqli_query($link, "SELECT prijs FROM product WHERE productnummer=$productnummer");
        $Resultaat_aantal       =      mysqli_query($link, "SELECT aantal FROM product WHERE productnummer=$productnummer"); 
     
    $productnummer   =   mysqli_fetch_assoc($Resultaat_productnummer);    
    $naam_tab        =   mysqli_fetch_assoc($Resultaat_naam);                                          //Ophaling van gegevens Database
    $omschrijving    =   mysqli_fetch_assoc($Resultaat_omschrijving);
    $afmetingen      =   mysqli_fetch_assoc($Resultaat_afmetingen);
    $prijs           =   mysqli_fetch_assoc($Resultaat_prijs);
    $aantal          =   mysqli_fetch_assoc($Resultaat_aantal); 
 
$productnummer  = implode ($productnummer);    
$naam_tab       = implode ($naam_tab);                                                                 //Implode = samenpersing array tot string 
$omschrijving   = implode ($omschrijving);
$afmetingen     = implode ($afmetingen);
$prijs          = implode ($prijs);
$aantal         = implode ($aantal);

?> 

<html>
    <head>
        <link rel='stylesheet' type='text/css' href='CssMartijn.css'>                                         
    </head>
       <body>
        <title><?php print($naam_tab); ?></title>
            <h1><?php print ($naam_tab); ?></h1>
                <p1><?php print ($omschrijving); ?></p1> 
                    <p2>Afmetingen<br><?php print($afmetingen); ?></p2> 
                       <p3>Prijs <?php print ($prijs); ?></p3>
                         <p4>In winkelwagentje</p4>
                         <?php                                                                                                                                    //Doorlinking winkelwagentje
                     Print("<a href ='winkelwagen-update.php?productnummer=$productnummer&naam=$naam_tab&prijs=$prijs&aantal=$aantal'>                                              
                           <img src = 'winkelwagentje.jpg'>
                           </a>");
                          ?>
       </body>
</html>

