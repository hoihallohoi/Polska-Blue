<!DOCTYPE html>
<?php
error_reporting(E_ALL);
session_start();

$user = "";
$pass = "";
$con = new mysqli("localhost", "root", "usbw", "login semester2", 3307);

// Check connection
if (!$con) {
    echo "<div>";
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    echo "</div>";
}

if (isset($_SESSION["inloggen"]) && $_SESSION["inloggen"]) {
    echo "You are already logged in, " . $_SESSION['inloggen'] . "! <br> I'm Loggin you out M.R ..";
    unset($_SESSION);
    session_destroy();
    exit;
}

$loggedIn = false;

$userName = isset($_POST["E-mailadres"]) ? $_POST["E-mailadres"] : null;
$userPass = isset($_POST["password"]) ? $_POST["password"] : null;
if ($userName && $userPass) {

    
    if(!empty($userName)&& !empty($userPass)){
        if($stmt = $con->prepare( "SELECT * FROM inloggegevens WHERE Gebruikersnaam = ? AND Wachtwoord = ?")){
            $stmt->bind_param( "ss", $userName, $userPass);
            $stmt->execute();
            $stmt->store_result();   
            $row = $stmt->num_rows();
        }
    }
    else {
      $row = 0;  
    }
    //oud
    if ($row != 1) {
        echo "<div>";
        echo "<script>alert('Email or password is not correct, try again!')</script>";
        echo "</div>";
    } else {

        $loggedIn = true;
    }
}
session_destroy();
if (!$loggedIn) {
    echo "
        <html>
    <head>
              <meta charset='UTF-8'>
        <title>Log in</title>
    </head>
    <body>
        <fieldset style='width:30%'><legend>Log in om artikelen in uw winkelwagen te leggen.</legend>
                <form method='post' action='oefening2.php' >
                <input type='text' name='E-mailadres' placeholder='E-mailadres' size='40px' > <br />
                <input type='password' name='password' placeholder='Wachtwoord' size='40px'> <br /><br />
                <input type='submit' value='Inloggen'>  
                <input type='submit' name='registreren' value='Registreren?'> <br /><br />
            </form>
       </fieldset>
       </body>
</html>
            ";
} else {
    echo "<div>";
    echo "You have been logged in as $userName!";
    echo "</div>";
    $_SESSION["inloggen"] = $userName;
}
?>
