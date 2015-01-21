<!--Gemaakt door: Martijn Bakker-->
<!DOCTYPE html>

        <?php
        
        $link = new mysqli("localhost","root","usbw","polskablue",3307); 
        $stmt = mysqli_prepare($link, "INSERT INTO winkelwagen (productnummer, gebruikernummer, aantal) VALUES(?, ?, ?)");
        
        $productnummer = $_GET["productnummer"];
        
        $link_winkelwagen = new mysqli("localhost","root","usbw","product",3307);
        $stmt = mysqli_prepare($Link, "DELETE =?,
                                       FROM winkelwagen");
        $stmt->param($stmt, 'i', $productnummer); 
        $stmt->execute();
        header( 'Location: /Winkelwagen.php' ) ;
        ?>

