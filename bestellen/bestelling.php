<?php
function __autoload($class_name) {
    include $class_name.'.php';
}


$klantnummer=1;
$email='gaksfug@jashflah.hid';

$Bestellings_functies = new Bestelling_functies;

if(isset($_POST['producten'])){
	$Bestellings_functies->bestelling_plaatsen($email,$klantnummer,$_POST['producten'],$_POST['aantallen']);
}
else{
	$Bestellings_functies->opmaak_alles($klantnummer);
}
?>