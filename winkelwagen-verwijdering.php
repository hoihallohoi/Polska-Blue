<!--Gemaakt door: Martijn Bakker-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        $productnummer = $_GET["productnummer"];
        
        $link_winkelwagen = mysqli_connect("localhost","root","usbw","product",3307);
        
        mysqli_query($link_winkelwagen, "DELETE productnummer 
                                         FROM winkelwagen");
        
        ?>
    </body>
</html>
