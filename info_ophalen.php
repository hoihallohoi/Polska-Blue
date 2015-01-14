<!--PHP deel gemaakt door Richard Kooijker
Connectie tussen website info & database -->

<?php
include('opmaak.php');
Sessiestart();
//Connecting met sql db
$conn = mysqli_connect("localhost", "root", "usbw", "polskablue", 3307);

$naam = array();
//haalt alle links op en plaatst deze in array $naam 
if ($stmt = mysqli_prepare($conn, "SELECT links FROM informatie_polskablue")) {// geen WHERE
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $info);
    $i = 0;
    while (mysqli_stmt_fetch($stmt)) {
        ($naam[$i] = $info);
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
Head();
            //Connecting met sql db
            $conn = mysqli_connect("localhost", "root", "usbw", "polskablue", 3307);
            //Laat alleen info zien als je op menu item hebt geklikt
            if (isset($_GET['links'])) {
                $link = $_GET['links'];


                // haalt alle informatie uit de db en plaatst deze in array $informatie
                $stmt = mysqli_prepare($conn, "SELECT informatie FROM informatie_polskablue WHERE links='" . $link . "'");
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $descriptie);

               print ($descriptie);
                
            } 
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
            <?php
            //elke naam die bestaat in $naam array wordt geprint op scherm
            print"<ul>";
            //voor elke waarde dat de array bevat print hij op het scherm
            foreach ($naam as $printName) {
                $printString = getListObject($printName);//dit is de $output waarde in de functie die je returnt & toewijst
                print ($printString);
            }
            print"</ul>";
            // hiermee print je de waarde die je uit de array hebt opgehaald tussen deze regel
            function getListObject($input) {
                $output = "<li><a href='info_ophalen.php?links=" . $input . "'</a>" . $input . "</li>";
                return $output;
            }
            ?>
        </div>
    </body>
</html>