<!DOCTYPE html>
<!--PHP deel gemaakt door Richard Kooijker
Connectie tussen website info & database -->

<html>
    <head>
        <title>PolskaBlue</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <form class="right" method="post">
            <input formaction="registratie.php" type="submit" value="Registreren">
            <input formaction="login/inlogscherm.php" type="submit" value="Inloggen">
        </form>
        <br>
        <br>
        <img class="left" src="Foto/IMG_9060.JPG">
        <img class="fotomid" src="Foto/tweerev1.jpg">

        <img class="right" src="Foto/IMG_9061.JPG"><br>
        <br>
        <div class="leftnav" id="nav">
            <form>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li>Winkelwagen</li>
                    <li>Uw Gegevens</li>
                    <li>Bestellen</li> 
                </ul>
            </form>
        </div>
        <div class="mid">
            <?php
            //Connecting met sql db
            $conn = mysqli_connect("localhost", "root", "usbw", "info_toevoegen", 3307);

            //hier haal je de GET op die meegegeven is
            $links = $_GET["links"];
           
            //haalt alle links op en plaatst deze in array $naam 
            if($stmt = mysqli_prepare($conn, "SELECT links FROM informatie_polskablue")){// geen WHERE
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $info);
                $naam = array();
                $i = 0;
                while (mysqli_stmt_fetch($stmt)) {
                    ($naam[$i] = $info . "<br>");
                    $i++;
                }
            }
          
            // haalt alle informatie uit de db en plaatst deze in array $informatie
            $stmt = mysqli_prepare($conn, "SELECT informatie FROM informatie_polskablue"); // geen WHERE
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $descriptie);
            $informatie = array();
            $i = 0;
            while (mysqli_stmt_fetch($stmt)) {
                ($informatie[$i] = $descriptie . "<br>");
                $i++;
            }
            
            // haalt alle url's op uit de db en plaatst deze in array $url
            $stmt = mysqli_prepare($conn, "SELECT url FROM informatie_polskablue"); // geen WHERE
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $info);
            $url = array();
            $i = 0;
            while (mysqli_stmt_fetch($stmt)) {
                ($url[$i] = $info);
                $i++;
            }
      print_r ($url);

//elke naam die bestaat in $naam array wordt geprint op scherm + ULR word meegegeven
            $ii = 0;
            print"<ul>";
            foreach ($url as $printurl) {
                
                
                print"<li>";
                print"<a href='";
                print ($printurl);
                print"'>";
                print ($naam[$ii]);
                
                print"</li></a>";
                $ii++;
            }
            print"</ul>";


            mysqli_close($conn)
            ?>
        </div>
        <img class="right" src="Foto/IMG_8964.JPG">
        <div class="left"><br><h2>Producten</h2>
            <form>
                <ul>
                    <li><a href="productenoverzicht.php?categorie=Bekers_en_mokken">Bekers &Mokken</a></li>
                    <li><a href="productenoverzicht.php?categorie=Borden">Borden</a></li>
                    <li><a href="productenoverzicht.php?categorie=Diverse_schalen">Diverse Schalen</a></li>
                    <li><a href="productenoverzicht.php?categorie=Alles_voor_het_ontbijt">Alles voor het ontbijt</a></li>
                    <li><a href="productenoverzicht.php?categorie=Theepotten_en_theelichten">Theepotten en Theelichten</a></li>
                    <li><a href="productenoverzicht.php?categorie=Appelpotten_en_voorraadpotten">Appelpotten & Voorraadpotten</a></li>
                    <li><a href="productenoverzicht.php?categorie=Knoflook_en_uienpotten">Knoflook en Uienpotten</a></li>
                    <li><a href="productenoverzicht.php?categorie=Diversen">Diversen</a></li>
                    <li><a href="productenoverzicht.php?categorie=Tafelaccessoires">Tafelaccessoires</a></li>
                </ul>
            </form>

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <img class="right" src="Foto/IMG_9042.JPG">
        <div class="left"><h2>Informatie</h2>
            <form>
                <ul>
                    <li><a href="info_ophalen.php?links=herkomst_bunzlau">Herkomst Bunzlau</a></li>
                    <li>Over mij</li>
                    <li>Openingstijden Showroom</li>
                    <li>Cadeaubonnen</li>
                    <li>Betaalwijze</li>
                    <li>Verzendkosten</li>
                    <li>Levertijden</li>
                    <li>Privacy Verklaring</li>
                    <li>Algemene Voorwaarden</li>
                    <li>Agenda Markten en Fairs</li>
                    <li>Foto's</li>
                    <li>Contact</li>
                </ul>
            </form>
        </div>
    </body>
</html>
