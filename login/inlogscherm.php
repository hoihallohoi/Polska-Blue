<!DOCTYPE html>
<?php
//$userName = isset($_POST["E-mailadres"]) ? $_POST["E-mailadres"] : null;
//$userPass = isset($_POST["password"]) ? $_POST["password"] : null;

function __autoload($class_name) {
    include 'class.'.$class_name.'.inc';
}


if(empty($_POST["password"]) || empty($_POST["E-mailadres"])){
	echo "
		<html>
			<head>
				<meta charset='UTF-8'>
				<title>Log in</title>
			</head>
		<body>
			<fieldset style='width:30%'><legend>Log in om artikelen in uw winkelwagen te leggen.</legend>
					<form method='post' action='' >
					<input type='text' name='E-mailadres' placeholder='E-mailadres' size='40px' > <br />
					<input type='password' name='password' placeholder='Wachtwoord' size='40px'> <br /><br />
					<input type='submit' value='Inloggen'>  
					<input type='submit' name='registreren' value='Registreren?'> <br /><br />
				</form>
		   </fieldset>
		   </body>
	</html>";
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
