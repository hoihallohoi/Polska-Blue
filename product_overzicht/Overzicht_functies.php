<?php
class Overzicht_functies{
	//artikelen per pagina
	public static $app = 20;
        public static $velden = array('naam','categorienaam','afmetingen_inhoud','aantal','prijs');
		//soorteering volgorde
		public static $sgve = array('ASC','DESC');
		
    function zoeken($page,$woord,$veld,$soorter,$volgorde ){
        $db = Connect::getInstance();
        $mysqli = $db->getConnection();
        $page = ($page-1)*self::$app;
        $veld = $this->valid_check($veld,self::$velden);
        $volgorde = $this->valid_check($volgorde,self::$sgve);
        $soorter = $this->valid_check($soorter,self::$velden);
        $query="SELECT productnummer,naam,prijs,aantal,categorienaam,afmetingen_inhoud FROM product WHERE ".$veld." LIKE CONCAT('%', ?, '%') ORDER BY ".$soorter." ".$volgorde." LIMIT ? , ".self::$app;
		if($stmt = $mysqli->prepare($query)) {
                    $stmt->bind_param('si',$woord,$page);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($id,$naam,$prijs,$aantal,$soort,$afmetingen);
        }
        $table = "<table>";
        $table .= "<tr class='title_overzicht_table'>";
		$table .= "<form method='POST'>";
		$table .= "<input type='submit' value='verwijderen'>";
		$table .="<td >selecteren</td>";
        foreach(self::$velden as $veldnaam){
            $table .= "<td><a href='".$this->sorteringlink($veldnaam)."'>".$veldnaam."</a></td>";
        }
        $table .="<td>aanpassen</td>"
                ."</tr>";
        while($stmt->fetch()){
            $table .= "<tr><td ><input type='checkbox' name='product_selectie[]'  value='".$id."'></td><td>".$naam."</td><td>".$soort."</td><td>".$afmetingen."</td><td>".$aantal."</td><td>".$prijs."</td><td><a href='./aanpassen?".$id."'>aanpassen<a></td></tr>";
        }
		$table .= "</form>";
        $table .= "</table>";
        return $table;
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
   
   function product_remove($producten,$mysqli){
	$vals[0] = '';
	$query = 'DELETE from product where productnummer IN (';
	//voor elk product een ? en een i
	foreach($producten as $productnr){
		$query .= '?,';
		$vals[0] .= 'i';
		$vals[] = $productnr;
	}
	$query =rtrim ($query , ',');
	$query .= ')';
	//call user array funct pakt alleen gerefereerde arrays dus daarom moet het op deze mannier gedaan worden
	if($stmt = $mysqli->prepare($query)) {
		foreach ($vals as &$value){
			$param[] = &$value;
		}
		call_user_func_array(array($stmt, 'bind_param'), $param);
		$stmt->execute();
		//vraag hiet later naar
/*		if($stmt->mysql_affected_rows() != count($producten) ){
			echo 'sommige van de velden konden niet worden verwijderd omdat ze nog ';
		} */
	}
}
  }
  

?>