<?php
include('login/class.Login.inc');
function Sessiestart(){
		session_start();
        $link = mysqli_connect("localhost", "root", "usbw", "polskablue", 3307);
		$waarde = Login::login_check($link);;
    return $waarde;
}

function Head(){
    print('<html>
    <head>
        <title>PolskaBlue</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>');
    $ingelogd = Sessiestart();
    if ($ingelogd == true){
		if(isset($_POST['uitloggen']) &&  $_POST['uitloggen'] == true){
			session_destroy();
			header('');
		}
		else{
			print('<a href="PGoverzicht.php">'.$_SESSION['account_naam'].'</a>');
			echo '<form method="post"><input name="uitloggen" type="submit" value="Submit"></form>';
		}
    }
	else{
        print('<form class="right" method="post">
            <input formaction="registratie.php" type="submit" value="Registreren">
            <input formaction="login/inlogscherm.php" type="submit" value="Inloggen">
		</form>');         
    }
	print('<br>
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
        <div class="mid">');
}
function Bottom(){
    print('</div>
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
                    <li><a href="info_ophalen.php?links=over_mij">Over mij</a></li>
                    <li><a href="info_ophalen.php?links=openingstijden_showroom">Openingstijden Showroom</a></li>
                    <li><a href="info_ophalen.php?links=cadeaubonnen">Cadeaubonnen</a></li>
                    <li><a href="info_ophalen.php?links=betaalwijze">Betaalwijze</a></li>
                    <li><a href="info_ophalen.php?links=verzendkosten">Verzendkosten</a></li>
                    <li><a href="info_ophalen.php?links=levertijden">Levertijden</a></li>
                    <li><a href="info_ophalen.php?links=privacy_verklaring">Privacy Verklaring</a></li>
                    <li><a href="info_ophalen.php?links=algemene_voorwaarden">Algemene Voorwaarden</a></li>
                    <li><a href="info_ophalen.php?links=agenda_markten_en_fairs">Agenda Markten en Fairs</a></li>
                    <li><a href="info_ophalen.php?links=fotos">Fotos</a></li>
                    <li><a href="info_ophalen.php?links=contact">Contact</a></li>
                </ul>
            </form>
        </div>
    </body>
</html>');
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

