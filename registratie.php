<!DOCTYPE html>
<!-- Gemaakt door Atze de Groot -->
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css.css" />
    </head>
    <body>
        <form action="registratie.php" method="post">
            <fieldset>
                <legend><h1><b>Registratie</b></h1></legend><br>
                <h3>Voer de volgende gegevens in om een account aan te maken.</h3><br>
                <h3>Bij de verplichte velden staat er een * voor</h3><br><br>
                <label>*Voornaam:</label> <input class="tekstveld" type="text" name="voornaam" maxlength="25" value= <?php
                if (isset($_POST["registreer"])) {
                    print($_POST["voornaam"]);
                }
                ?>><br><br>

                <label>*Achternaam:</label> <input class="tekstveld" type="text" name="achternaam" maxlength="35" value= <?php
                if (isset($_POST["registreer"])) {
                    print($_POST["achternaam"]);
                }
                ?>><br><br>

                <label>*E-mailadres:</label> <input class="tekstveld" type="text" name="email" maxlength="254" value= <?php
                if (isset($_POST["registreer"])) {
                    print($_POST["email"]);
                }
                ?>><br><br>

                <label>*Woonplaats:</label> <input class="tekstveld" type="text" name="woonplaats" maxlength="25" value= <?php
                if (isset($_POST["registreer"])) {
                    print($_POST["woonplaats"]);
                }
                ?>><br><br>

                <label>*Postcode:</label> <input class="tekstveld" type="text" name="postcode" maxlength="6" value= <?php
                if (isset($_POST["registreer"])) {
                    print($_POST["postcode"]);
                }
                ?>><br><br>

                <label>*Adres:</label> <input class="tekstveld" type="text" name="adres" maxlength="35" value= <?php
                if (isset($_POST["registreer"])) {
                    print($_POST["adres"]);
                }
                ?>><br><br>

                <label>Telefoonnummer:</label> <input class="tekstveld" type="text" name="telefoonnummer" maxlength="11" value= <?php
                if (isset($_POST["registreer"])) {
                    print($_POST["telefoonnummer"]);
                }
                ?>><br><br>

                <label>*Wachtwoord:<div class="kleinetekst">(minimaal 6 tekens)</div></label> <input class="tekstveld" type="password" name="wachtwoord1" maxlength="25"><br><br>

                <label>*Wachtwoord bevestigen:</label> <input class="tekstveld" type="password" name="wachtwoord2" maxlength="25"><br><br><br><br>

                <input type="submit" class="knop" name="registreer" value="Registreer">
                <input type="submit" class="knop" name="annuleer" value="Annuleer">
            </fieldset>

            <?php
            $link = mysqli_connect("localhost", "root", "usbw", "klanten", "3307");

            if (isset($_POST["registreer"])) { //checken of de registratie knop is ingedrukt
                if (!empty($_POST["voornaam"]) && !empty($_POST["achternaam"]) && !empty($_POST["email"]) && !empty($_POST["woonplaats"]) && !empty($_POST["postcode"]) && !empty($_POST["adres"]) && !empty($_POST["wachtwoord1"]) && !empty($_POST["wachtwoord2"])) {
                    //checken of alle velden niet leeg zijn
                    //variabelen koppelen aan de invoer
                    $voornaam = $_POST["voornaam"];
                    $achternaam = $_POST["achternaam"];
                    $email = $_POST["email"];
                    $woonplaats = $_POST["woonplaats"];
                    $postcode = $_POST["postcode"];
                    $adres = $_POST["adres"];
                    $telefoonnummer = $_POST["telefoonnummer"];
                    $wachtwoord1 = $_POST["wachtwoord1"];
                    $wachtwoord2 = $_POST["wachtwoord2"];

                    $activation = 0;
                    $hash = sha1(rand(0, 1000)); //random getal genereren die aan het account in de database gekoppeld zal worden
                    $msg = 'Uw account is aangemaakt, <br /> Klik op de link die naar uw mail is gestuurd om uw account te activeren.';

                    $emailcheck = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';

                    if (preg_match($emailcheck, $email)) { //checken of E-mail geldig is
                        if (strlen($wachtwoord1) >= 6) { //moet nog naar gekeken worden, want werkt ook als je een getal invoerd groter dan 9!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                            // if (!preg_match('/[^A-Za-z0-9]+/', $wachtwoord1)) {
                            if ($wachtwoord1 == $wachtwoord2) { //controleren of de wachtwoorden overeen komen
                                $stmtemail = mysqli_prepare($link, "SELECT emailadres FROM klantgegevens WHERE emailadres = ?");
                                mysqli_stmt_bind_param($stmtemail, "s", $email);
                                mysqli_stmt_execute($stmtemail);
                                $row = mysqli_stmt_fetch($stmtemail);
                                if ($email == $row) { //checken of het E-mailadres al in de database staat
                                    print("Dit E-mailadres is al in gebruik");
                                    exit();
                                } else {
                                    $wachtwoord = $wachtwoord1; //wanneer de 2 wachtwoorden overeen komen
                                    $encryptwachtwoord = sha1($wachtwoord); // wachtwoord encrypten
                                    $stmt = mysqli_prepare($link, "INSERT INTO klantgegevens (voornaam, achternaam, emailadres, woonplaats, postcode, adres, telefoonnummer, wachtwoord, activation, hash) VALUES (?,?,?,?,?,?,?,?,?,?)");
                                    mysqli_stmt_bind_param($stmt, "ssssssssis", $voornaam, $achternaam, $email, $woonplaats, $postcode, $adres, $telefoonnummer, $encryptwachtwoord, $activation, $hash);
                                    mysqli_stmt_execute($stmt);
                                    print($msg);

                                    //email
                                    $to = $email;
                                    $subject = 'Activering | Verificatie';
                                    $message = '
 
                            Bedankt voor uw registratie!
                            Uw account is aangemaakt, U kunt uw account activeren door op de link hier onder aan de pagina te klikken.
 

                            Klik hier om uw account te activeren:
                            http://www.yourwebsite.com/verify.php?email=' . $email . '&hash=' . $hash . '
 
                            ';

                                    $headers = 'From:polskablue@gmail.com' . "\r\n";
                                    mail($to, $subject, $message, $headers);
                                }
                            } else {
                                print("Wachtwoorden komen niet overeen.");
                                //       }
                                //  } else {
                                //        print("Niet alfanumerieke tekens zijn niet toegestaan");
                            }
                        } else {
                            print("Wachtwoord moet minimaal 6 tekens bevatten");
                        }
                    } else {
                        print("Het ingevoerde E-mailadres is ongeldig");
                    }
                } else {
                    print("Niet alle gegevens zijn ingevuld.");
                }
            }




            //    http://www.9lessons.info/2013/11/php-email-verification-script.html
            //    http://code.tutsplus.com/tutorials/how-to-implement-email-verification-for-new-members--net-3824
            //    http://stackoverflow.com/questions/8015098/how-to-check-if-value-already-exists-in-mysql-database
            ?>
        </form>
    </body>

</html>


