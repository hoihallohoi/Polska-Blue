<!--Gemaakt door Atze de Groot-->

<!DOCTYPE html>
<html>
    <head>
        <title>Verlanglijst</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

        <?php
        include 'connect.php';

        $_SESSION["gebruikersnummer"] = 4;
        $gebruikersnummer = $_SESSION["gebruikersnummer"];

        if (isset($_POST["verwijderalles"])) {
            mysqli_query($link, "DELETE FROM verlanglijstje WHERE gebruikersnummer = $gebruikersnummer");
        }

        if (isset($_POST["allestoevoegen"])) {
            $aantal = 1;
            $query = mysqli_query($link, "SELECT * FROM verlanglijstje WHERE gebruikersnummer = $gebruikersnummer");
            $array = mysqli_fetch_assoc($query);
            
            while ($array) {
                print($gebruikersnummer);
                $productnummerArray = $array["productnummer"];
                mysqli_query($link, "INSERT INTO winkelwagen  VALUES($gebruikersnummer, $productnummerArray, $aantal)");
                print_r($array);
                $array = mysqli_fetch_assoc($query);
            }
        }

        if (isset($_GET["verwijder"])) {
            $productNummer = $_GET["verwijder"];

            $stmtDelete = mysqli_prepare($link, "DELETE FROM verlanglijstje WHERE gebruikersnummer = ? AND productnummer = ?");
            mysqli_stmt_bind_param($stmtDelete, "ii", $gebruikersnummer, $productNummer);
            mysqli_stmt_execute($stmtDelete);
        }

        //moet naar gekeken worden, klopt niet!!!!!!!!!!!!!!!!!!!!!!!!
        if (isset($_GET["toevoegen"])) {
            $productNummertoevoeg = $_GET["toevoegen"];
            $aantal = 1;
           
            $stmtToevoegen = mysqli_prepare($link, "INSERT INTO winkelwagen VALUES(?, ?, ?)");
            mysqli_stmt_bind_param($stmtToevoegen, "iii", $gebruikersnummer, $productNummertoevoeg, $aantal);
            mysqli_stmt_execute($stmtToevoegen);
        }
        ?>

        <fieldset>
            <legend><h1><b>Verlanglijst</b></h1></legend><br>
            <h3>Hier is een overzicht van producten die u aan uw verlanglijst hebt toegevoegd</h3><br><br><br>
            <form action="verlanglijstje.php" method="post">
                <input class="knop" type="submit" name="allestoevoegen" value="Alles toevoegen aan winkelwagen">
                <input class="knop" type="submit" name="verwijderalles" value="Verwijder alles"><br><br>
            </form>  
            <hr>

            <?php
            $zoeken = mysqli_query($link, "SELECT count(*) FROM verlanglijstje WHERE gebruikersnummer = $gebruikersnummer");
            $match = mysqli_fetch_assoc($zoeken);
            $match = implode($match);

            if ($match > 0) {
                $results = mysqli_query($link, "SELECT * FROM verlanglijstje WHERE gebruikersnummer = $gebruikersnummer");
                ?>
                <table style="width:100%">
                    <tr>
                        <th>
                    <div class='tabeltekst'>Product</div>
                    </th>
                    <th>
                    <div class='tabeltekst'>Afmetingen</div>
                    </th>
                    <th>
                    <div class='tabeltekst'>Categorie</div>
                    </th>
                    </tr>

                    <?php
                    while ($row = mysqli_fetch_assoc($results)) {
                        $nummer = $row["productnummer"];
                        $resultsProduct = mysqli_query($link, "SELECT * FROM product WHERE productnummer = $nummer");

                        while ($rowproduct = mysqli_fetch_assoc($resultsProduct)) {
                            ?>
                            <tr>
                                <td>
                                    <div class='tabeltekst'><img src='<?php echo $rowproduct["afbeelding"]; ?>'><br><br><?php echo $rowproduct["naam"]; ?> </div>
                                </td>
                                <td>
                                    <div class='tabeltekst'><?php echo $rowproduct["afmetingen_inhoud"]; ?> </div>
                                </td>

                                <td>
                                    <a href="#">
                                        <div class='tabeltekst'><?php echo $rowproduct["categorienaam"]; ?></div>
                                    </a>
                                </td>
                                <td>
                                    <form action="verlanglijstje.php" method="get">
                                        <button type="submit" class="knop2" name="verwijder" value="<?php echo $rowproduct["productnummer"]; ?>">Verwijder</button>

                                </td>
                                <td>
                                    <button type="submit" class="knop2" name="toevoegen" value="<?php echo $rowproduct["productnummer"]; ?>">Toevoegen aan winkelwagen</button>  
                                    </form>
                                </td>
                            </tr><?php
            }
        }
                    ?> </table> <?php
                } else {
                    echo "U heeft nog geen producten aan uw verlanglijst toegevoegd.";
                }
                ?>
        </fieldset>
    </body>
</html>