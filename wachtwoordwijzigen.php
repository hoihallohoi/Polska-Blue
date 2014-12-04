<!DOCTYPE html>
<!-- Gemaakt door Atze de Groot -->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css.css" />
    </head>
    <body>

        <?php
        //if(isset($_SESSION["id"])){
        $_SESSION["id"] = 73;
        ?>

        <form method="post">
            <fieldset>
                <legend><h1><b>Wachtwoord wijzigen</b></h1></legend><br>
                <h3>Druk op opslaan om uw nieuwe wachtwoord op te slaan</h3><br><br>
                <label>Oud wachtwoord:</label> <input class="tekstveld" type="password" name="oudww"><br><br>

                <label>Nieuw wachtwoord:<div class="kleinetekst">(minimaal 6 tekens)</div></label> <input class="tekstveld" type="password" name="nieuwww"><br><br>

                <label>Nieuw wachtwoord bevestigen:</label> <input type="password" class="tekstveld" name="nieuwwwbev"><br><br><br><br>

                <input formaction="wachtwoordwijzigen.php" class="knop" type="submit" name="opslaan" value="Opslaan">
                <input formaction="mijnaccount.php" class="knop" type="submit" name="annuleer" value="Annuleer">
            </fieldset>

            <?php
            if (isset($_POST["opslaan"])) {
                if (!empty($_POST["oudww"]) && (!empty($_POST["nieuwww"]) && (!empty($_POST["nieuwwwbev"])))) {
                    if ($_POST["nieuwww"] == $_POST["nieuwwwbev"]) {

                        $oudww = $_POST["oudww"];
                        $nieuwww = $_POST["nieuwww"];
                        $nieuwwwbev = $_POST["nieuwwwbev"];
                        $encryptOudww = sha1($oudww);
                        $encryptNieuwww = sha1($nieuwww);

                        if (strlen($nieuwww) >= 6) {
                            $link = mysqli_connect("localhost", "root", "usbw", "klanten", "3307");

                            $stmtww = mysqli_prepare($link, "SELECT wachtwoord FROM klantgegevens WHERE wachtwoord = ? AND id = ?");
                            mysqli_stmt_bind_param($stmtww, "si", $encryptOudww, $_SESSION["id"]);
                            mysqli_stmt_execute($stmtww);
                            $row = mysqli_stmt_fetch($stmtww);
                            mysqli_stmt_close($stmtww);

                            if ($oudww == $row) { //checken of het wachtwoord in de database staat
                                $stmt = mysqli_prepare($link, "UPDATE klantgegevens SET wachtwoord = ? WHERE wachtwoord = ? AND id = ?");
                                mysqli_stmt_bind_param($stmt, "ssi", $encryptNieuwww, $encryptOudww, $_SESSION["id"]);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                                print("Uw wachtwoord is opgeslagen!");
                            } else {
                                print("Het ingevulde wachtwoord is incorrect");
                            }
                        } else {
                            print("Uw wachtwoord moet minimaal 6 tekens bevatten");
                        }
                    } else {
                        print("Wachtwoorden komen niet overeen");
                    }
                } else {
                    print("Niet alle gegevens zijn ingevuld");
                }
            }

            //} else {
            //    print("U moet zijn ingelogd om uw wachtwoord te veranderen.");
            //}
            ?>
        </form>
    </body>
</html>