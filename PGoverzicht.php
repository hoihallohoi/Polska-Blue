<!DOCTYPE html>
<!--Gemaakt door: Atze de Groot-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css.css" />
    </head>
    <body>

        <?php
        //if (isset($_SESSION["id"])) {
        $_SESSION["id"] = 73;

        include "getUserinfo.php";
        ?>
        <fieldset>
            <legend><h1><b>Overzicht Persoonlijke gegevens</b></h1></legend><br>
            <h3>Hier is een overzicht van uw persoonlijke gegevens</h3><br><br>
            <label>Voornaam:</label> <div class="overzicht"><?php print(getUserinfo("voornaam")); ?></div><br><br>

            <label>Achternaam:</label> <div class="overzicht"><?php print(getUserinfo("achternaam")); ?></div><br><br>

            <label>Woonplaats:</label> <div class="overzicht"><?php print(getUserinfo("woonplaats")); ?></div><br><br>

            <label>Postcode:</label> <div class="overzicht"><?php print(getUserinfo("postcode")); ?></div><br><br>

            <label>Adres:</label> <div class="overzicht"><?php print(getUserinfo("adres")); ?></div><br><br>

            <label>Telefoonnummer:</label> <div class="overzicht"><?php print(getUserinfo("telefoonnummer")); ?></div><br><br><br><br><br><br><br>

            <form action="mijnaccount.php">
                <input class="knop" type="submit" name="terug" value="Terug">
            </form>
        </fieldset>

        <?php
        //} else {
        //    print("U moet zijn ingelogd om deze pagina te kunnen bezoeken.");
        //}
        ?>
    </body>
</html>
