$Database_gegevens['voornaam'] array(freak,pieter,klaas,greta,ferdinant);
$Database_gegevens['achternaam'] array(klaasen,henken,mcklaasentown,something,ferdinant);
$Database_gegevens['plaats'] array('Meppel','Zwolle','Awsomeville','Faketown','aslkdjflk');
$Database_gegevens['postcode'] array('AA','AB','HR','TR','HX','QZ','UI');

for($i,$i => 1000,$i++){
	INSERT INTO gebruikers(gebruikersid,postcode,voornaam,achternaam,gebruikersnaam,wachtwoord,salt,huisnummer,woonplaats)
	VALUES (
	$i,
	$Database_gegevens['postcode'][rand(0,9)].rand(1000,9999),
	$Database_gegevens['voornaam'][rand(0,9)],
	$Database_gegevens['achternaam'][rand(0,9)],
	'somethingsomething',
	'latrox1',
	3,
	rand(1,99),
	$Database_gegevens['plaats'][rand(0,9)],
	);
}
foreach(){

}