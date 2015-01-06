
<html>
    <head>
        <meta charset="UTF-8">
            <link rel="stylesheet" type="text/css" href="Productoverzicht.css">
        <title>Bunzlau winkelwagen</title>
    </head>
    <body>
        <img src = "Logo.jpg" alt = "Logo">
            <h1>Winkelwagen</h1> 
        
<?php   

       $link = new mysqli("localhost","root","usbw","polskablue",3307);
       
        $stmt = mysqli_prepare($link, "SELECT productnummer, naam, omschrijving, afmeting_inhoud, prijs, aantal FROM product WHERE productnummer=?");
        $stmt->bind_param('i',$productnummer);                                                    //Bind productnummer als interger
        $stmt->execute();                                                                         //Voert de voel uit
        $stmt->store_result();                                                                    //Plaatst resultaat 
          $stmt->bind_result($productnaam, $product_omschrijving, $product_afmeting, $product_prijs, $product_aantal);         //Bind de results aan waarden
            $stmt->fetch($productnaam, $product_omschrijving, $product_afmeting, $product_prijs, $product_aantal);             //Ophaling ter weegeving resultaten
                                                                             
 ?>
            <table>
                <tr>
                    <th>Naam</th>
                    <th>Prijs</th>
                    <th>Aantal</th>
                    <th>_____</th>
                </tr>
                <tr>
                    <td><?php print ($productnaam); ?></td>
                    <td><?php print ($product_prijs); ?></td>
                    <td><?php print ($product_aantal); ?></td>
           <?php
             print("<a href ='winkelwagen-verwijdering.php?&productnummer=$productnummer'>
                    <td>Verwijderen</td> 
                    </a>"); 
            ?>
                </tr>
            </table>
           <?php
           print("<a href = 'index.php'>
               <h2>Verder winkelen</h2>
                    </a>");
           print("<a href = 'bestellen.php') 
               <h3>Afrekenen</h3> 
                    </a>");
            ?>
    </body>
</html>

