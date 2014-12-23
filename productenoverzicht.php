<?php
include('opmaak.php');
Sessiestart();
Head();
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
            Bottom();
            ?>
       