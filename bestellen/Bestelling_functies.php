<?php

class Bestelling_functies{
	function bestelling_plaatsen($email,$klantnummer, array $product,array $aantal){
		$db = Connect::getInstance();
        $mysqli = $db->getConnection();
		//insert van de bestelling als totaal
		$qeury_insert = 'INSERT INTO bestelling (klantnummer, productnummer, aantal , aanvraag_datum) VALUES ';
		//verwijdering van de bestelde producten uit de voorraad
		$query_update = 'UPDATE product SET aantal = CASE productnummer';
		// paar waarder prepareren
		$vals[0] = '';
		$qeury_fields  = '';
		$query_update_nummers = '';
		//zorg er voor dat er minimaal 1 product moet zijn bestelt
	if(count($product) > 0){
		//maakt een 
		foreach($product as $key => $nummer){
			//zet de positie van de velden
			$qeury_fields .= '(? , ? , ?, NOW() ),';
			//zet de typen voor de prepared statement
			$vals[0] .= 'iii';
			//zet de waarde van velden
			$vals[] = $klantnummer;
			$vals[] = $nummer;
			$vals[] = $aantal[$key];
			//weet nog niet zeker of dit gaat werken
			//ik kan deze dingen er zo inzetten omdat de gegevens al voorkomen in ander deel van de query dus als er een probleem is word deze daar all tegen gehouden
			$query_update .= ' WHEN "'.$nummer.'" THEN aantal-'.$aantal[$key];
			$query_update_nummers .= $nummer.',';
		}
		$query_update .= ' END WHERE productnummer IN('.rtrim ($query_update_nummers , ',').')';
			
		//haalt de laatste komma weg van query fields zo dat deze goed aansluit op de rest van de query
		$qeury_fields =rtrim ($qeury_fields , ',');
		//maak de insert af
		$qeury_insert .= $qeury_fields;
		//verwijderd deze uit winkel wagen
		$query_delete = 'DELETE from winkelwagen where gebruikernummer = ?';
			
		
		if($stmt = $mysqli->prepare($qeury_insert) && $stmt = $mysqli->prepare($query_delete) && $stmt = $mysqli->prepare($query_update)){
			$stmt = $mysqli->prepare($qeury_insert);
			
			//call user array funct pakt alleen gerefereerde arrays dus daarom moet het op deze mannier gedaan worden
			foreach ($vals as &$value){
				$param[] = &$value;
			}
			call_user_func_array(array($stmt, 'bind_param'), $param);
			$stmt->execute();
			$stmt->close();
			
			$stmt = $mysqli->prepare($query_update);
			$stmt->execute();
			$stmt->close();
			
			$stmt = $mysqli->prepare($query_delete);
			$stmt->bind_param('i',$klantnummer);
			$stmt->execute();
			$stmt->close();
			
			
			$this->send_mail($klantnummer,$email,$product);
			
		}
		else{
				echo 'er is een probleem opgetreden 1';
		}
	}
}

	function send_mail($klantnummer,$email,$productnummers){
		$db = Connect::getInstance();
        $mysqli = $db->getConnection();
		$query = 'SELECT product.afbeelding,product.naam,product.prijs,bestelling.aantal,product.afmetingen_inhoud FROM product JOIN bestelling ON bestelling.productnummer=product.productnummer WHERE bestelling.klantnummer = ? AND bestelling.aanvraag_datum = NOW() ';
		if($stmt = $mysqli->prepare($query)){
			$stmt->bind_param('i',$klantnummer);
			$stmt->store_result();
			$stmt->bind_result($plaatje,$naam,$prijs,$aantal,$afmetingen_inhoud);
			
			$totaalprijs ='';
			
			$table = '<table class="besetllingen"  border="1">';
			$table .='<td>afbeelding</td><td>naam</td><td>aantal</td><td>prijs</td><td>totaal</td><td>afmeting/inhoud</td>';
			 while($stmt->fetch()){
				 $totaalprijs = $totaalprijs+($prijs*$aantal);
				 $table .=
						'<tr><td><imgbox class="bestelling_plaatje"><img src="productimgages/'.$plaatje.'" ></imgbox></td>'.
						'<td>'.$naam.'</td>'.
						'<td>'.$aantal.'</td>'.
						'<td>'.$prijs.'</td>'.
						'<td>'.$prijs*$aantal.'</td>'.
						'<td>'.$afmetingen_inhoud.'</td></tr>';
			 }
			
			$table .= '</table>';
			$table .=  '<span class="totaalbedrag_bestelling"><p>totaal bedrag</p><p>'.$totaalprijs.'</p></span>';
			
			$msg1 = 'something something';
			$msg1 .= $table;
			echo $table;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: Mary <'.$email.'>,' . "\r\n";
			$headers .= 'From:polskablue <admin@example.com>' . "\r\n";

			mail("admin@email.com","bestelling".$email,$msg1,$headers);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: Mary <'.$email.'>,' . "\r\n";
			$headers .= 'From:polskablue <admin@example.com>' . "\r\n";
			mail($email,"polskablue bestelling",$msg2,$headers);
			
			return true;
		}
		else{
			echo 'er is een probleem opgetreden';
		}
	}

	
	function opmaak_alles($klantnummer){
		$db = Connect::getInstance();
        $mysqli = $db->getConnection();
		$query = 'SELECT winkelwagen.productnummer,product.afbeelding,product.naam,product.prijs,winkelwagen.aantal,product.afmetingen_inhoud FROM product JOIN winkelwagen ON winkelwagen.productnummer=product.productnummer WHERE winkelwagen.gebruikernummer = ? ORDER BY product.prijs';
		if($stmt = $mysqli->prepare($query)) {
				$stmt->bind_param('i',$klantnummer);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($productnr,$plaatje,$naam,$prijs,$aantal,$afmetingen_inhoud);
		 }
		 echo '<div class="bestellingen"><h3>bestelling</h3>';
		 $totaalprijs = 0;
		 $submit = '<form method="POST">';
		 $table = '<table class="besetllingen">';
		 $table .='<td>afbeelding</td><td>naam</td><td>aantal</td><td>prijs</td><td>totaal</td><td>afmeting/inhoud</td>';
		 if($stmt->num_rows > 0){
			 while($stmt->fetch()){
				 $submit .= '<input type="hidden" name="producten[]" value="'.$productnr.'" >';
				 $submit .= '<input type="hidden" name="aantallen[]" value="'.$aantal.'" >';
				 $totaalprijs = $totaalprijs+($prijs*$aantal);
				 $table .=
						'<tr><td><imgbox class="bestelling_plaatje"><img src="productimgages/'.$plaatje.'" ></imgbox></td>'.
						'<td>'.$naam.'</td>'.
						'<td>'.$aantal.'</td>'.
						'<td>'.$prijs.'</td>'.
						'<td>'.$prijs*$aantal.'</td>'.
						'<td>'.$afmetingen_inhoud.'</td></tr>';
			 }
			
			$table .= '</table>';
			$table .=  '<span class="totaalbedrag_bestelling"><p>totaal bedrag</p><p>'.$totaalprijs.'</p></span>';
			echo $table;
			$submit .= '<input type="hidden" name="aantallen[]" value="'.$aantal.'" >';
			$submit .= '<input name="bestellen" type="submit" Bestellen afronden ></form>';
			echo $submit;
		}
		else{
			header("Location: ../");
		}
	}
		
}