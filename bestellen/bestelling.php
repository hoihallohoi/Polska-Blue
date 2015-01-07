<?php
function __autoload($class_name) {
    include $class_name.'.php';
}
//zorgt er voor dat als je niet bent ingellogt je weer terug gaat naar de home page
if(isset($ingelogd) && $ingelogd != false){

//beetje een probleem hier om het verder te maken moest ik gebruik maken van klant nummer maar de database was nog niet omgezet
//$klantnummer=$_SESSION['$_SESSION['account_naam']'];
$klantnummer=1;

$Bestellings_functies = new Bestelling_functies;

//maakt de eigenlijke pagina
$Bestellings_functies->opmaak_alles($klantnummer);

}
else{
	header('location : ../');
}
?>