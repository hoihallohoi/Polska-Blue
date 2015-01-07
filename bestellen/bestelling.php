<?php
function __autoload($class_name) {
    include $class_name.'.php';
}


$klantnummer=0;

$Bestellings_functies = new Bestelling_functies;


$Bestellings_functies->opmaak_alles($klantnummer);

echo "<html><body>".
	"<head><link rel='stylesheet' type='text/css' href='CSS/bestelling.css'></head>";

echo "</body></html>"

?>