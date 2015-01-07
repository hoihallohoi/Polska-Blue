<!--Gemaakt door: Martijn Bakker-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php    
        
        $klantnummer=1;
        $productnummer = $_GET["productnummer"];
        $naam = $_GET["naam"];
        $prijs = $_GET["prijs"];
        $aantal = $_GET["aantal"];
        
        $link = new mysqli("localhost","root","usbw","polskablue",3307); 
        $stmt = mysqli_prepare($link, "INSERT INTO winkelwagen (productnummer, gebruikernummer, aantal) VALUES(?, ?, ?)");
        $stmt->bind_param($stmt, 'iii', $productnummer, $klantnummer,$aantal);
        if (!$stmt->execute());  {
           $stmt = mysqli_prepare($link_winkelwagen, "UPDATE winkelwagen SET aantal = (?+1) WHERE productnummer =?, gebruikernummer = ?");
                $stmt->bind_param($stmt, 'iii', $aantal, $productnummer, $klantnummer); 
                $stmt->execute();  
                }
        
        ?>
    </body>
</html>
