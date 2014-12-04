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
        //if (isset($_SESSION["id"])) {
        include "getUserinfo.php";
        $_SESSION["id"] = 73;

        $link = mysqli_connect("localhost", "root", "usbw", "klanten", "3307");
        if (isset($_POST["opslaan"])) {
            if (!empty($_POST["voornaam"]) && (!empty($_POST["achternaam"]) && (!empty($_POST["woonplaats"]) && (!empty($_POST["postcode"]) && (!empty($_POST["adres"])))))) {
                $stmt = mysqli_prepare($link, "UPDATE klantgegevens SET voornaam = ?, achternaam = ?, woonplaats = ?, postcode = ?, adres = ?, telefoonnummer = ?  WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "ssssssi", $_POST["voornaam"], $_POST["achternaam"], $_POST["woonplaats"], $_POST["postcode"], $_POST["adres"], $_POST["telefoonnummer"], $_SESSION["id"]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                print("Uw wijzigingen zijn opgeslagen");
            } else {
                print("Niet alle gegevens zijn ingevuld");
            }
        }
        // } else {
        //     print("U moet zijn ingelogd om uw persoonlijke gegevens te veranderen.");
        // }
        ?>

        <form method="post">
            <fieldset>
                <legend><h1><b>Persoonlijke gegevens wijzigen</b></h1></legend><br>
                <h3>Druk op opslaan om de wijzigingen op te slaan</h3><br>
                <h3>Bij de verplichte velden staat er een * voor</h3><br><br>

                <label>*Voornaam:</label> <input class="tekstveld" type="text" name="voornaam" value= <?php print(getUserinfo("voornaam")); ?>><br><br>

                <label>*Achternaam:</label> <input class="tekstveld" type="text" name="achternaam" value= <?php print(getUserinfo("achternaam")); ?>><br><br>

                <label>*Woonplaats:</label> <input class="tekstveld" type="text" name="woonplaats" value= <?php print(getUserinfo("woonplaats")); ?>><br><br>

                <label>*Postcode:</label> <input class="tekstveld" type="text" name="postcode" value= <?php print(getUserinfo("postcode")); ?>><br><br>

                <label>*Adres:</label> <input class="tekstveld" type="text" name="adres" value= <?php print(getUserinfo("adres")); ?>><br><br>

                <label>Telefoonnummer:</label> <input class="tekstveld" type="text" name="telefoonnummer" value= <?php print(getUserinfo("telefoonnummer")); ?>><br><br><br><br>

                <input formaction="PGwijzigen.php" class="knop" type="submit" name="opslaan" value="Opslaan">

                <input formaction="mijnaccount.php" class="knop" type="submit" name="annuleer" value="Annuleer">
            </fieldset>
        </form>
    </body>
</html>
