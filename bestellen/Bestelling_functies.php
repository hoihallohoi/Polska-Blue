<?php
class Bestelling_functies{
	function bestelling_inserter($email,$klantnummer, array $product){
		$db = Connect::getInstance();
        $mysqli = $db->getConnection();
		//insert van de bestelling als totaal
		$qeury = 'INSERT INTO bestelling (klantnummer, productnummer, aantal , aanvraag_datum) VALUES ';
		$vals[0] = '';
		//voor elke bestelling maakt deze een value aan
		if(!count($productnummer['productnummer']) == 0){
			foreach($$product['nummer'] as $nummer){
				$qeury_fields .= '(? , ? , ?, ? ),';
				$vals[0] .= 'iiis';
				$vals[] = $klantnummer;
				$vals[] = $nummer;
				$vals[] = $aantal;
				$vals[] = date('Y-m-d H:i:s');
				$msg .= '';
			}
		}
		$qeury_fields =rtrim ($qeury_fields , ',');
		$qeury .= $qeury_fields;
		$qeury .= ' DELETE from winkelwagen where gebruikersnummer = ?';
		$vals[] = $klantnummer;
		
		if($stmt = $mysqli->prepare($query)){
			call_user_func_array(array($stmt, 'bind_param'), $vals);
			$stmt->execute();
			
			$msg = '';
			
			mail("admin@email.com","bestelling".$klantnummer,$msg);
			mail($email,"polskablue bestelling",$msg);
		}
		else
			echo 'er is een probleem opgetreden';
	}



	function opmaak_alles($klantnummer){
		$db = Connect::getInstance();
        $mysqli = $db->getConnection();
		$query = 'SELECT winkelwagen.productnummer,product.afbeelding,product.naam,product.prijs,winkelwagen.aantal,product.afmetingen_inhoud FROM product JOIN winkelwagen ON winkelwagen.productnummer WHERE winkelwagen.gebruikernummer = ? ORDER BY';
		if($stmt = $mysqli->prepare($query)) {
				$stmt->bind_param('i',$klantnummer);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($productnr,$plaatje,$naam,$prijs,$aantal,$afmetingen_inhoud);
		 }
		 echo '<div class="bestellingen"><h3>bestelling</h3>';
		 $totaalprijs = 0;
		 $submit = '<form method="POST">';
		 while($stmt->fetch()){
		 $submit .= '<input type="hidden" name="producten[nummer]" value="'.$productnr.'" >';
		 $submit .= '<input type="hidden" name="producten[aantal]" value="'.$aantal.'" >';
		 $totaalprijs = $totaalprijs+($prijs*$aantal);
		 echo
			'<table class="besetllingen">'.
				'<imgbox class="bestelling_plaatje"><img src="productimgages/'.$plaatje.'" ></imgbox>'.
				'<tr><td>naam</td><td>'.$naam.'</td></tr>'.
				'<tr><td>aantal</td><td>'.$aantal.'</td></tr>'.
				'<tr><td>prijs</td><td>'.$prijs.'</td></tr>'.
				'<tr><td>totaal</td><td>'.$prijs*$aantal.'</td></tr>'.
				'<tr><td>afmeting/inhoud</td><td>'.$afmetingen_inhoud.'</td></tr>'.
			'</table>';
		 }
		echo '<span class="totaalbedrag_bestelling"><p>totaal bedrag</p><p>'.$totaalprijs.'</p></span>';
		$submit .= '<input type="submit" Bestellen afronden ></form>';
		echo $submit;
		}
		
}