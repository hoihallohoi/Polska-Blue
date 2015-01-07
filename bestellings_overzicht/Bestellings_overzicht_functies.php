<?php
//in hindsight vind ik dat ik dit hele ding slecht gemaakt

class Bestellings_overzicht_functies{
	//default aantal producten per pagina
    public static $app = 20;
	//velden waarop gezocht mag worden en die worden weer gegeven
    public static $velden = array('achternaam','voornaam','woonplaats','postcode','naam','adres');

    function zoeken($page,$woord,$veld,$soorter,$volgorde,$mysqli ){
		//zet de pagina voor query
        $page = ($page-1)*self::$app;
		//controleerd of de in de URL opgegeven velden zijn toegestaan
        $veld = $this->valid_check($veld,self::$velden);
		$volgorde = $this->valid_check($volgorde,array('ASC','DESC'));
		$soorter = $this->valid_check($soorter,self::$velden);
        //frustreerend ik weet nu hoe het beter kan maar er is geen tijd meer
		//berijd de querys voor ik mag ze er op deze mannier inzetten omdat ik er maar een paar mogelijk heden zijn die ik eerder all controleer
		$query ="SELECT geb.id,CONCAT_WS(' ',geb.voornaam,geb.achternaam) AS naam,geb.woonplaats,geb.adres,geb.postcode FROM bestelling JOIN gebruiker AS geb ON geb.id = bestelling.klantnummer WHERE ".$veld." LIKE CONCAT('%', ?, '%') GROUP BY geb.id ORDER BY ".$soorter." ".$volgorde." LIMIT ? , ".self::$app ;
        $query_product="SELECT product.productnummer,product.naam,product.prijs,bestelling.aantal FROM product JOIN bestelling ON product.productnummer = bestelling.productnummer WHERE bestelling.klantnummer = ? ";
		if($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('si',$woord,$page);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($klantnummer,$gebruikernaam,$woonplaats,$adres,$postcode);
			
			//hier begin ik met de opmaak van de pagina
			$table = "<div class='volgorde_controlle'>";
			//self:: betekent dat ik een static element pak van aangegeven
			foreach(self::$velden as $veldnaam){
				//stop elk veld in een functie die er een link van maakt die precies de zelfde pagina weer geeft maar dan in andere volgorde en met de pagina nummering op 1
				$table .= "<div><a href='".$this->sorteringlink($veldnaam)."'>".$veldnaam."</a></div>";
			}
             $table .= "</div>";
			 //voeg de database klant gegevens in
            while($stmt->fetch()){
                $table .= "<div class='bestelling'>";
                $table .= "<div class='klant_informatie_blok'>"
                        . "<div class='klant_informatie'>naam: ".$gebruikernaam."</div>"
						. "<div class='klant_informatie'>naam: <form method='POST'><input type='submit' value='verwijderen'><input type='hidden' name='verwijder_bestellingen' value='".$klantnummer."'></form></div>"
                        . "</div>"
                        . "<div class='klant_informatie_blok'>"
                        . "<div class='klant_informatie'><p>stad: </p><p>".$woonplaats."</p></div>"
                        . "<div class='klant_informatie'><p>adres: </p><p>".$adres."</p></div>"
                        . "<div class='klant_informatie'><p>postcode: </p><p>".$postcode."</p></div>"
                        . "</div>";
						
				//query voor het product (ben hier zelf niet tevreden mee)
                if($stmt2 = $mysqli->prepare($query_product)) {
                    $stmt2->bind_param('i',$klantnummer);
                    $stmt2->execute();
                    $stmt2->store_result();
                    $stmt2->bind_result($productnummer,$productnaam,$prijs,$aantal_bestelt);
					
                    $table .= "<table><tr class='table_head'>";
                    //stel de tabel namen in
					foreach(array('productnaam','prijs','aantal bestelt','totaal prijs van product') as $veldnaam){
                        $table .= "<td >".$veldnaam."</td>";
                    }
                    $table .="</tr>";
					//zet de gegevns in de tabel
                    while($stmt2->fetch()){
                      $table .="<tr><td>".$productnaam."</td><td>".$prijs."</td><td>".$aantal_bestelt."</td><td>".$prijs*$aantal_bestelt."</td></tr>";
                    }
                    $table .= "</table>";
                    $stmt2->close();
                }
                else{
                    echo 'sir error vond does not exist jet';
                }
                $table .= "</div>";
            }
            $table .= "</div>";
            return $table;
        }
    }
    
    //spreek behoorlijk voor zich kan je afvragen of dit nodig is maar het is makkelijk voor errors
    function valid_check($veld ,array $velden){
        if(in_array($veld,$velden)){
            return $veld;
        }
        else{
            echo '</br>Sir error von not jet made jet '.$veld.'</br>';
        }
    }
	
    function pages($veld,$woord,$mysqli){
		//controleert of het veld is toegestaan
       $veld = $this->valid_check($veld,self::$velden);
		//
       $query = "SELECT COUNT(bestelling.klantnummer) FROM bestelling JOIN gebruiker ON gebruiker.id = bestelling.klantnummer WHERE ".$veld." LIKE CONCAT('%', ?, '%')";
	   if($stmt = $mysqli->prepare($query)) {
         $stmt->bind_param('s',$woord);
         $stmt->execute();
         $stmt->bind_result($aantal);
         $stmt->fetch();
		 //berekening voor het aantal pagina's is het totaal aantal gegevens / het aantal gegevens per bladzij naar boven afgerond
         $pages = $aantal/self::$app;      
       }
       return ceil($pages);
    }
	
    function navigatie($pages){
	
	//checkt of de pagina al is gezet en het een getal is
        if(isset($_GET['page']) && is_numeric($_GET['page'])){
			// als pagina's meer is dan het maximum aan pagina's ga naar de laatste pagina
			if($_GET['page'] > $pages){
				header("Location: ".$this->page_replacer($pages));
				$current_page = $pages;
			}
			else{
				// de pagina is de opgegeven pagina
				$current_page = $_GET['page'];
			}
        }
        else{
			//default pagina
            $current_page = 1;
        }
        
        //maken van het invoer veld voor de pagina
        $nav= "<form  class='voorraad_beheer_nav' action='' >";
        //pakt all gets behalve page dit zorgt ervoor dat zodat als het word gesubmit alles het zelfde is behalve de pagina
		foreach($_GET as $name => $value){
            if($name != 'page')
				$nav .= "<input name='".$name."' type='hidden' value='".$value."'>";
        }
        $nav .= "<input name='page' type='text' value='".$current_page."'>";
        $nav .= "<div class='voorraad_beheer_nav_buttons'>";

		//maken van de knoppen voor de pagina nummering
        if($current_page == $pages){
            $nav .= "<a class='' ></a>";
        }
        else{
				//functie waar hier naar word gerevereerd zorgt er voor dat alles het zelfde is behalve dat de link 1 pagina meer heeft die daar onder doet het zelfde maar dan 1 minder
                $nav .= "<a class='' href='".$this->page_replacer( $current_page+1 )."'></a>";
        }
        if($current_page == 1){
            $nav .=  "<a class='down'></a>";
        }
        else{
            $nav .= "<a class='down' href='".$this->page_replacer( $current_page-1 )."' ></a>";
             
        }
        
        $nav .= "</div></form>";
        return $nav;
    }
    
	//regelt het soorteren van gegevens
    function sorteringlink($name){
        //waarop je wilt soorteren
		$desired_select =  'gesoorteert_op='.$name;
        //zet de pagina naar 1
		$link = $this->page_replacer('1');
        //controleert of de volgorde all eens eerder is gezet
		if(isset($_GET['volgorde']) && isset($_GET['gesoorteert_op']) ){
            $current_select = 'gesoorteert_op='.$_GET['gesoorteert_op'];
            //maakt huidige URL selectie aan
            $current_volgorde = 'volgorde='.$_GET['volgorde'];
            $current_selectie = 'gesoorteert_op='.$_GET['gesoorteert_op'];
            //checkt of je all eerder op de knop hebt gedrukt
            if($_GET['gesoorteert_op'] == $name){
                //checkt wat de huidige volgorde is
               if($_GET['volgorde'] == 'DESC'){
                    $order = 'ASC';
                }else{
                    $order = 'DESC';
                }
            }
			//als er nog geen volgorde is is het altijd ASC
            else{
                $order = 'ASC';
            }
			//zet de link
             $desired_volgorde = 'volgorde='.$order;
             $link = str_replace ( $current_volgorde, $desired_volgorde,$link);
             $link = str_replace ( $current_selectie, $desired_select,$link);
        }
        else{
            //de default is naam die staat niet in de url maar als er op word geklikt veranderd als nog de volgorde
            if($name== 'naam'){
                $order = 'DESC';
            }
            else{
                $order = 'ASC';
            }
            $desired_volgorde = 'volgorde='.$order;
            //zet de link
            $desired_volgorde = $desired_volgorde = "&".$desired_volgorde;
            $link .=  $desired_volgorde."&".$desired_select;
        }
        return $link;
    } 
    
	
   function page_replacer( $replace  ){
		//over blijfsel van oude code niet echt een rede om weg te halen omdat het handig is voor mogelijke uitbrijding
       $subject = 'page';
	   //pakt de huidige URL
       $current_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       //$current_link =end( explode( "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]","/" ));
	   //controleert of er all een pagina is gezet
      if(isset($_GET[$subject])){
		//huidige status van de link
        $current = $subject.'='.$_GET[$subject];
        //gewenste status
		$replace = $subject.'='.$replace;
        //zet de gewenste status door deze te vervangen binnen de link
		return str_replace($current,$replace,$current_link );
      }
	  //controleert of er all gets in url zijn om te bepalen welk teken nodig is en zet dan de link
      else if(count($_GET) == 0){
        $current_link = $current_link."?".$subject."=".$replace;
      }
      else{
        $current_link = $current_link."&".$subject."=".$replace;;
      }
      
      return $current_link ;
   }
   
   //verwijderd bestellingen hoe het dat doet is behoorlijk straight forwerd
   function verwijder_bestellingen($klantnummer,$mysqli){
	$query = 'DELETE from bestelling where klantnummer IN (?)';
	if($stmt = $mysqli->prepare($query)) {
		$stmt->bind_param('s',$klantnummer);
		$stmt->execute();

	}
	
  }
 }
 ?>