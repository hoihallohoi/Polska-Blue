
            <?php
            include('opmaak.php');
            Head();
            
            //Connecting met sql db
                $conn = mysqli_connect("localhost", "root", "usbw", "info_toevoegen", 3307);
            
            //hier haal je de GET op die meegegeven is
            $links=$_GET["links"];
            //prepared statement voor data ophalen      
            $stmt = mysqli_prepare($conn, "SELECT informatie FROM informatie_polskablue WHERE links=?");        
            mysqli_stmt_bind_param($stmt, "s", $links);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $info);
            mysqli_stmt_fetch($stmt);
            print ($info);
            
            mysqli_close($conn);
            Bottom();

            ?>