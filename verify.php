<!DOCTYPE html>
<!-- Gemaakt door Atze de Groot -->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $link = mysqli_connect("localhost", "root", "usbw", "klanten", "3307");
        if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {

            $activation = 0;
            $active = 1;
            $email = $_GET["email"];
            $hash = $_GET["hash"];

            $emailquery = mysqli_prepare($link, "SELECT emailadres FROM klantgegevens WHERE emailadres = ?");
            mysqli_stmt_bind_param($emailquery, "s", $email);
            mysqli_stmt_execute($emailquery);
            mysqli_stmt_bind_result($emailquery, $email1);
            mysqli_stmt_fetch($emailquery);
            mysqli_stmt_close($emailquery);
            

            $hashquery = mysqli_prepare($link, "SELECT hash FROM klantgegevens WHERE hash = ?");
            mysqli_stmt_bind_param($hashquery, "s", $hash);
            mysqli_stmt_execute($hashquery);
            mysqli_stmt_bind_result($hashquery, $hash1);
            mysqli_stmt_fetch($hashquery);
            mysqli_stmt_close($hashquery);

            //checken of de E-mail en hash van de link in de database voorkomen en het account nog niet geactiveerd is
            $search = mysqli_prepare($link, "SELECT count(emailadres) FROM klantgegevens WHERE emailadres = ? AND hash = ? AND activation = ?");
            mysqli_stmt_bind_param($search, "ssi", $email1, $hash1, $activation);
            
            mysqli_stmt_execute($search);
            mysqli_stmt_bind_result($search, $match);
            mysqli_stmt_fetch($search);
            mysqli_stmt_close($search);
           // $match = mysqli_stmt_num_rows($search); //aantal treffers tellen



            if ($match > 0) { //als aantal treffers meer is dan 0 dan wordt de activation op 1 gezet en is het account geactiveerd
                $setActive = mysqli_prepare($link, "UPDATE klantgegevens SET activation = ? WHERE emailadres = ? AND hash = ? AND activation = ?");
                mysqli_stmt_bind_param($setActive, "issi", $active, $email1, $hash1, $activation);
                mysqli_stmt_execute($setActive);

                print("Uw account is geactiveerd, u kunt nu inloggen");
            } else {
                print ("De url is niet geldig of uw account is al geactiveerd.");
            }
        } else {
            print("Ongeldige invoer, klik op de link die naar uw E-mailadres is verstuurd.");
        }
        ?>
    </body>
</html>
