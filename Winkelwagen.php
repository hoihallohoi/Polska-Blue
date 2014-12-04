
<html>
    <head>
        <meta charset="UTF-8">
            <link rel="stylesheet" type="text/css" href="CSS.css">
        <title>Bunzlau winkelwagen</title>
    </head>
    <body>
        <img src = "Logo.jpg" alt = "Logo">
            <h1>Winkelwagen</h1> 
        
<?php
        
       $link = mysqli_connect ("localhost" , "root" , "usbw" , "product" , "3307");
       
       $Resultaat_klantnummer = mysqli_query ($link, "SELECT klantnummer FROM winkelwagen");
       $Resultaat_productnummer = mysqli_query ($link, "SELECT productnummer FROM winkelwagen"); 
       $Resultaat_naam = mysqli_query ($link, "SELECT naam FROM product"); 
       $Resultaat_prijs = mysqli_query ($link, "SELECT prijs FROM product"); 
       $Resultaat_aantal = mysqli_query ($link, "SELECT aantal FROM winkelwagen");
       
       $naam = mysqli_fetch_assoc($Resultaat_naam);
       $prijs = mysqli_fetch_assoc ($Resultaat_prijs);
       $aantal = mysqli_fetch_assoc ($Resultaat_aantal);
       
       $naam        =   implode ($naam);
       $prijs       =   implode ($prijs); 
       $aantal      =   implode ($aantal); 
       
?>
            <table>
                <tr>
                    <th>Naam</th>
                    <th>Prijs</th>
                    <th>Aantal</th>
                    <th>_____</th>
                </tr>
                <tr>
                    <td><?php print ($naam); ?></td>
                    <td><?php print ($prijs); ?></td>
                    <td><?php print ($aantal); ?></td>
                   <?php
             print("<a href ='winkelwagen-verwijdering.php?&productnummer=$productnummer'>
                    <td>Verwijderen</td> 
                    </a>"); 
                    ?>
                </tr>
            </table>
            <h2>Verder winkelen</h2>
            <h3>Afrekenen</h3> 
            
    </body>
</html>

