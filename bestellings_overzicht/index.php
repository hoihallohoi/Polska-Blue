<?php

echo '<html><head><link rel="stylesheet" type="text/css" href="CSS/VoorraadBeheer.css"><meta charset="UTF-8"></head><body>';
function __autoload($class_name) {
    include $class_name.'.php';
}
$db = Connect::getInstance();
$mysqli = $db->getConnection();
$Overzicht_functies = new Bestellings_overzicht_functies;

if(isset($_GET['zoekterm'])){
    $zoekterm = $_GET['zoekterm'];
}
else{
    $zoekterm = '';
}
if(isset($_GET['zoekselectie']) || !empty($_GET['zoekselectie'])){
    $zoekselectie = $_GET['zoekselectie'];
}
else{
    $zoekselectie = 'voornaam';
}
if(isset($_GET['volgorde'])){
    $volgorde= $_GET['volgorde'];
}
else{
    $volgorde= 'ASC';
}
if(isset($_GET['gesoorteert_op'])){
    $gesorteerd_op = $_GET['gesoorteert_op'];
}
else{
    $gesorteerd_op = 'voornaam';
}
if(isset($_GET['page'])){
    $page = $_GET['page'];
}
else{
    $page = 1;
}
if(isset($_POST['verwijder_bestellingen'])){
	$Overzicht_functies->verwijder_bestellingen($_POST['verwijder_bestellingen'],$mysqli);
}

echo "<div class='voorraad_beheer_main'>";
echo "<h2>Voorraad beheer</h2>";
echo "<div class='voorraad_beheer_menu'>";
echo $Overzicht_functies->navigatie($Overzicht_functies->pages($zoekselectie,$zoekterm,$mysqli));

echo "<form class='voorraad_beheer_zoeken_main'>"
        . "<div>"
            . "<select name='zoekselectie'>"
                . "<option>voornaam</option>"
                . "<option>achternaam</option>"
                . "<option>woonplaats</option>"
                . "<option>postcode</option>"
                . "<option>straatnaam</option>"
                . "<option>totaal_prijs</option>"
                . "<option></option>"
            ."</select>"
        . "</div>"
        ."<div><input type='text' name='zoekterm'></div>"
        ."<div><input type='submit' value='zoeken'></div>"
    . "</form>";
echo "</div>";

echo $Overzicht_functies->zoeken($page,$zoekterm,$zoekselectie,$gesorteerd_op,$volgorde,$mysqli );
echo "</div>";


?>