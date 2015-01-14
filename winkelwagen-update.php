<!--Gemaakt door: Martijn Bakker-->
<!DOCTYPE html>

        <?php    
        
        $klantnummer=1;
        $productnummer = $_GET["productnummer"];
        $naam = $_GET["naam"];
        $prijs = $_GET["prijs"];
        $aantal = $_GET["aantal"];
        
        $link = new mysqli("localhost","root","usbw","polskablue",3307); 
        $stmt = mysqli_prepare($link, "INSERT INTO winkelwagen (productnummer, gebruikersnummer, aantal) VALUES(?, ?, ?)");
        $stmt->bind_param('iii', $productnummer, $klantnummer,$aantal);
        if (!$stmt->execute());  {
           $stmt = mysqli_prepare($link, "UPDATE winkelwagen SET aantal = (?+1) WHERE productnummer =? AND gebruikersnummer = ?");
                $stmt->bind_param('iii', $aantal, $productnummer, $klantnummer); 
                $stmt->execute();  
                }
            header( 'Location: /Winkelwagen.php' ) ;
        ?>

