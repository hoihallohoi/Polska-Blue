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
        ?>

        <fieldset>
            <legend><h1><b>Mijn account</b></h1></legend><br><br>
            <a href="PGoverzicht.php"><div class="link">Overzicht Persoonlijke gegevens</div></a><br>
            <a href="PGwijzigen.php."><div class="link">Persoonlijke gegevens wijzigen</div></a><br>
            <a href="wachtwoordwijzigen.php"><div class="link">Wachtwoord wijzigen</div></a><br>
        </fieldset>
        <?php
        //} else {
        //    print("U moet zijn ingelogd om deze pagina te kunnen bezoeken.");
        //}
        ?>
    </body>
</html>
