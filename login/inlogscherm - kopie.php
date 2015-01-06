<!DOCTYPE html>
<?php

$userName = isset($_POST["E-mailadres"]) ? $_POST["E-mailadres"] : null;
$userPass = isset($_POST["password"]) ? $_POST["password"] : null;

function __autoload($class_name) {
    include 'class.'.$class_name.'.inc';
}


if(empty($_POST["password"]) || empty($_POST["E-mailadres"])){
	echo "
	<head>
  <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
  <link rel='stylesheet' href='..//Css/inlogscherm.css'>
</head>
<body>
    <div class='ribbon'></div>
  <div class='login'>
  <h1>Inloggen</h1>
  <p>Log in om uw winkelwagen te bekijken</p>
      <form method='post' action='' >
    <div class='input'>
      <div class='blockinput'>
        <i class='icon-envelope-alt'></i><input type='mail' name='E-mailadres' placeholder='Email'>
      </div>
      <div class='blockinput'>
        <i class='icon-unlock'></i><input type='password' name='password' placeholder='Password'>
      </div>
    </div>
      <button><input type='submit' value='Inloggen'></button>
      <!--<input type='submit' name='registreren' value='Registreren?'> <br /><br />-->
  </form>
  </div>
  <br><br>
</body>
	";
}
else {
	$Login = new Login;
	$mysqli = mysqli_connect("localhost", "root", "usbw", "polskablue", 3307);
	$check = $Login->login_start($_POST["E-mailadres"], $_POST["password"], $mysqli);
        
	if(!is_string($check) && $check == true){
		echo "<div>";
		echo "u bent ingelogt als ".$_SESSION['account_naam']."!";
		echo "</div>";
	}
	else{
		echo $check;
		echo "er is een probleem";
	}
    /*
	
	*/
}
?>