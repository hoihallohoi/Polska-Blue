<!DOCTYPE html>
<!-- Gemaakt door Richard Kooijker -->

<html>
    <head>
        <meta charset='UTF-8'>
        <title>Info toevoegen</title>
    </head>
    <body>
        <fieldset style='width:30%'><legend>Voeg informatie toe</legend>

            <?php
            if (isset($_POST['opslaan'])) {
//Connecting met sql db
                $conn = mysqli_connect("localhost", "root", "usbw", "info_toevoegen", 3307);

                $text = trim($_POST["input"]);
//error controle
                if ($conn == false) {
                    trigger_error("kan geen verbinding maken met de database");
                }

//SQL query & prepared statements


                $query = mysqli_prepare($conn, 'UPDATE informatie_polskablue SET informatie=? where links = ?');
                mysqli_stmt_bind_param($query, "ss", $text, $conn);
                mysqli_stmt_execute($query);


//query bevestiging/error
                if (mysqli_query($conn, $query)) {
                    print "Bericht is succesvol toegevoegd!";
                } else {
                    print "Error: " . $query . "<br>" . mysqli_error($conn);
                }

                mysqli_close($conn);
            }
//SELECT query voor tonen huidige informatie | nog niet getest!
            $query = mysqli_prepare($conn, 'SELECT informatie FROM informatie_polskablue where links = ?');
            mysqli_stmt_bind_param($query, "ss", $text, $conn);
            mysqli_stmt_execute($query);


            mysqli_fetch_assoc($result);
            mysqli_fetch();
            mysqli_close($conn);
            ?> 

            <form method='post' action='info_toevoegen.php' >
                <textarea name="input" rows='4' cols='50' placeholder='Typ hier..'><?php $result ?></textarea> 
                <input type='submit' name="opslaan" value='Opslaan'>  
                <input type='submit' name="wijzig" value='Wijzig'> 

            </form>
        </fieldset>
    </body>
</html>








