<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
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
            $categorie = $_GET["categorie"];
            if (isset($_GET["page"])) {
                $page = $_GET["page"];
                if (isset($_GET["volgende"])) {
                    $page++;
                } else {
                    $page--;
                }
            } else {
                $page = 1;
            }
            $productenArray;
            $counter1 = 0;
            $getal = $page * 6;
            $counter2 = $getal - 6;
            $link = mysqli_connect("localhost", "root", "usbw", "polskablue", 3307);
            $stmt = mysqli_prepare($link, "SELECT productnummer, naam, omschrijving, prijs, afmetingen_inhoud, afbeelding FROM product WHERE categorienaam=?");
            mysqli_stmt_bind_param($stmt, "s", $categorie);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $code, $naam, $omschrijving, $prijs, $afmeting, $foto);
            while (mysqli_stmt_fetch($stmt)) {
                $productArray = array();
                $productArray["code"] = $code;
                $productArray["naam"] = $naam;
                $productArray["omschrijving"] = $omschrijving;
                $productArray["prijs"] = $prijs;
                $productArray["afmeting"] = $afmeting;
                $productArray["foto"] = $foto;
                $productenArray[$counter1] = $productArray;
                $counter1++;
            }
            while ($counter2 < $getal) {
                if (isset($productenArray[$counter2])) {
                    $tempproduct = $productenArray[$counter2];
                    print("<div class='leftfloat'><a href='productoverzicht.php?productcode=".$tempproduct["code"]."'<br>");
                    print("<img class='groot' src='Foto/".$tempproduct["foto"]."'><br>");
                    print("Naam:" . $tempproduct["naam"] . "<br>");
                    print("Afmeting:" . $tempproduct["afmeting"] . "<br>");
                    print("Omschrijving:" . $tempproduct["omschrijving"] . "<br>");
                    print("Prijs:" . $tempproduct["prijs"] . "<br></a></div>");
                    $counter2++;
                } else {
                    $counter2++;
                }
            }
            print("<form class='center' method='GET' action='index.php'>
            <input type='hidden' name='categorie' value='" . $categorie . "'>
            <input type='hidden' name='page' value='" . $page . "'>
            <input type='submit' name='vorige' value='Vorige'>
            <input type='submit' name='volgende' value='Volgende'>
        </form>");
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
                    <li>Herkomst Bunzlau</li>
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
