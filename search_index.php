<!-- Gemaakt door Richard Kooijker -->
<?php
echo '<html>
<head>
<title>Search</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href=""/>
</head>
<body>';
//Verbinding maken met datamase
$verbinding = mysqli_connect("localhost", "root", "usbw", "search", 3307);
include_once 'search_functions.php';
zoeken_bar();
//error controle
if ($verbinding == false) {
trigger_error("kan geen verbinding maken met de database");
}
//controleren of er gezocht is
if (!empty($_GET['query'])) {
//$page aangemaakt voor extra pagina's
//controleerd het huidige pagina nummer. Als pagina niet geset is = pagina 1
if (isset($_GET['page'])) {
$page = $_GET['page'];
} else {
$page = 1;
}
//prepared statements
$zoeken = $_GET['query'];
$page = ($page - 1) * 15;
$query = "SELECT id , naam, omschrijving, afmeting, prijs, categorienaam FROM product WHERE naam LIKE CONCAT ('%', ?, '%') limit ?,15";
$stmt = mysqli_prepare($verbinding, $query);
mysqli_stmt_bind_param($stmt, "si", $zoeken, $page);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $id, $naam, $omschrijving, $afmeting, $prijs, $categorie);
echo "<div>";
while (mysqli_stmt_fetch($stmt)) {
echo "<div class=''><div><h3>" . $naam . "<h3></div><div>" .
'Omschrijving: ' . $omschrijving . "</div></div>" .
'Afmeting: ' . $afmeting . "</div><div>" .
'Prijs: ' . $prijs . "</div></div>" .
'Catagorie : ' . $categorie . "</div></div>";
}
echo "</div>";
}
echo "</body></html>";
