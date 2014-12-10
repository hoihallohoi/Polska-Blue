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
        
        $link_winkelwagen = mysqli_connect("localhost","root","usbw","polskablue",3307);
        echo "INSERT INTO winkelwagen (productnummer, gebruikernummer, aantal) VALUES('".$productnummer."','".$klantnummer."',".$aantal.")";
        if (!mysqli_query($link_winkelwagen, "INSERT INTO winkelwagen (productnummer, gebruikernummer, aantal)
                                              VALUES('$productnummer','$klantnummer',$aantal")) {
            mysqli_query($link_winkelwagen,  "UPDATE winkelwagen 
                                              SET aantal = ($aantal+1)
                                              WHERE productnummer = $productnummer, gebruikernummer = $klantnummer");  
        }
        ?>
    </body>
</html>
