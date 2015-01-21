

<?php
echo '<html><head><link rel="stylesheet" type="text/css" href="CSS/VoorraadBeheer.css"><meta charset="UTF-8"></head><body>';


function __autoload($class_name) {
    include $class_name.'.php';
}

include_once 'Overzicht_functies.php';
$Overzicht_functies = new Overzicht_functies();
$db = Connect::getInstance();
$mysqli = $db->getConnection();

if(isset($_GET['zoekterm'])){
    $zoekterm = $_GET['zoekterm'];
}
else{
    $zoekterm = '';
}
if(isset($_GET['zoekselectie'])){
    $zoekselectie = $_GET['zoekselectie'];
}
else{
    $zoekselectie = 'naam';
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
    $gesorteerd_op = 'naam';
}
if(isset($_GET['page'])){
    $page = $_GET['page'];
}
else{
    $page = 1;
}
if(isset($_POST['product_selectie'])){
    $Overzicht_functies->product_remove($_POST['product_selectie'],$mysqli);
}

echo "<div class='voorraad_beheer_main'>";
echo "<h2>Voorraad beheer</h2>";
echo "<div class='voorraad_beheer_menu'>";
echo $Overzicht_functies->navigatie($Overzicht_functies->pages($zoekselectie,$zoekterm,$mysqli));

echo "<form class='voorraad_beheer_zoeken_main'>"
        . "<div>"
            . "<select name='zoekselectie'>";
foreach($Overzicht_functies::$velden as $zoeknaam){
	echo "<option>".$zoeknaam."</option>" ;
}
             
 echo   	"</select>"
        . "</div>"
        ."<div><input type='text' name='zoekterm'></div>"
        ."<div><input type='submit' value='zoeken'></div>"
    . "</form>";
echo "</div>";

echo $Overzicht_functies->zoeken($page,$zoekterm,$zoekselectie,$gesorteerd_op,$volgorde,$mysqli );
echo "</div>";

?>
    </body>
</html>
