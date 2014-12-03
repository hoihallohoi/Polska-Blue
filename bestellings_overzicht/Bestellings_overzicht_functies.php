<?php
class Bestellings_overzicht_functies{
    public static $app = 20;
    public static $velden = array('achternaam','voornaam','woonplaats','prijs','postcode','naam','straatnaam','huisnummer');

    function zoeken($page,$woord,$veld,$soorter,$volgorde ){
        $db = Connect::getInstance();
        $mysqli = $db->getConnection();
        $page = ($page-1)*self::$app;
        $veld = $this->valid_check($veld,self::$velden);
        $volgorde = $this->valid_check($volgorde,array('ASC','DESC'));
        $soorter = $this->valid_check($soorter,self::$velden);
        //$temp = $query ="(SELECT SUM(prijs) from product JOIN bestelling as bes ON product.productnummer = bes.productnummer WHERE bestelling.klantnummer = bes.klantnummer)";
		$query ="SELECT geb.gebruikersnummer,CONCAT_WS(' ',geb.voornaam,geb.achternaam) AS naam,geb.woonplaats,geb.straatnaam,geb.huisnummer,geb.postcode FROM bestelling JOIN gebruiker AS geb ON geb.gebruikersnummer = bestelling.klantnummer WHERE ".$veld." LIKE CONCAT('%', ?, '%') GROUP BY geb.gebruikersnummer ORDER BY ".$soorter." ".$volgorde." LIMIT ? , ".self::$app ;
        $query_product="SELECT product.productnummer,product.naam,product.prijs,product.aantal,bestelling.aantal FROM product JOIN bestelling ON product.productnummer = bestelling.productnummer WHERE bestelling.klantnummer = ? ";
        
        if($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('si',$woord,$page);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($klantnummer,$gebruikernaam,$woonplaats,$straatnaam,$huisnummer,$postcode);
            $table = "<div>";
            $header_velden = array('naam','woonplaats','prijs','aantal');
            $table .= "</div>";
             $table .= "<div class='volgorde_controlle'>";
             $table .= "<div><a href='".$this->sorteringlink('naam')."'>naam.</a></div>";
             $table .= "<div><a href='".$this->sorteringlink('woonplaats')."'>woonplaats</a></div>";
             $table .= "<div><a href='".$this->sorteringlink('postcode')."'>postcode</a></div>";
             $table .= "<div><a href='".$this->sorteringlink('straatnaam')."'>straatnaam</a></div>";
             $table .= "<div><a href='".$this->sorteringlink('huisnummer')."'>huisnummer</a></div>";
             $table .= "</div>";
            while($stmt->fetch()){
                $table .= "<div class='bestelling'>";
                $table .= "<div class='klant_informatie_blok'>"
                        . "<div class='klant_informatie'>naam: ".$gebruikernaam."</div>"
                        . "</div>"
                        . "<div class='klant_informatie_blok'>"
                        . "<div class='klant_informatie'><p>stad: </p><p>".$woonplaats."</p></div>"
                        . "<div class='klant_informatie'><p>adres: </p><p>".$straatnaam.$huisnummer."</p></div>"
                        . "<div class='klant_informatie'><p>postcode: </p><p>".$postcode."</p></div>"
                        . "</div>";
                if($stmt2 = $mysqli->prepare($query_product)) {
                    $stmt2->bind_param('i',$klantnummer);
                    $stmt2->execute();
                    $stmt2->store_result();
                    $stmt2->bind_result($productnummer,$productnaam,$prijs,$aantal_op_voorraad,$aantal_bestelt);

                    $table .= "<table><tr class='table_head'>";
                    foreach(array('productnaam','prijs','aantal bestelt','totaal prijs van product') as $veldnaam){
                        $table .= "<td >".$veldnaam."</td>";
                    }
                    $table .="</tr>";
                    while($stmt2->fetch()){
                      $table .="<tr><td>".$productnaam."</td><td>".$prijs."</td><td>".$aantal_op_voorraad."</td><td>".$prijs*$aantal_op_voorraad."</td></tr>";
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
    
    
    function valid_check($veld ,array $velden){
        if(in_array($veld,$velden)){
            return $veld;
        }
        else{
            echo '</br>Sir error von not jet made jet '.$veld.'</br>';
        }
    }
	
    function pages($veld,$woord){
       $db = Connect::getInstance();
       $mysqli = $db->getConnection();
       $veld = $this->valid_check($veld,self::$velden);

       $query = "SELECT COUNT(bestelling.klantnummer) FROM bestelling JOIN gebruiker ON gebruiker.gebruikersnummer = bestelling.klantnummer WHERE ".$veld." LIKE CONCAT('%', ?, '%')";
       if($stmt = $mysqli->prepare($query)) {
         $stmt->bind_param('s',$woord);
         $stmt->execute();
         $stmt->bind_result($aantal);
         $stmt->fetch();
         $pages = $aantal/self::$app;      
       }
       return ceil($pages);
    }
    function navigatie($pages){
        if(isset($_GET['page']) && is_numeric($_GET['page'])){
			if($_GET['page'] > $pages){
				header("Location: ".$this->page_replacer($pages));
				$current_page = $pages;
			}
			else{
				$current_page = $_GET['page'];
			}
        }
        else{
            $current_page = 1;
        }
        
        
        $nav= "<form  class='voorraad_beheer_nav' action='' >";
        foreach($_GET as $name => $value){
            if($name != 'page')
				$nav .= "<input name='".$name."' type='hidden' value='".$value."'>";
        }
        $nav .= "<input name='page' type='text' value='".$current_page."'>";
        $nav .= "<div class='voorraad_beheer_nav_buttons'>";

        if($current_page == $pages){
            $nav .= "<a class='' ></a>";
        }
        else{
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
    

    function sorteringlink($name){
        $desired_select =  'gesoorteert_op='.$name;
        $link = $this->page_replacer('1');
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
            else{
                $order = 'ASC';
            }
             $desired_volgorde = 'volgorde='.$order;
             $link = str_replace ( $current_volgorde, $desired_volgorde,$link);
             $link = str_replace ( $current_selectie, $desired_select,$link);
        }
        else{
            //checkt voor de default
            if($name== 'naam'){
                $order = 'DESC';
            }
            else{
                $order = 'ASC';
            }
            $desired_volgorde = 'volgorde='.$order;
            
            $desired_volgorde = $desired_volgorde = "&".$desired_volgorde;
            $link .=  $desired_volgorde."&".$desired_select;
        }
        return $link;
    } 
    
   function page_replacer( $replace  ){
       $subject = 'page';
       $current_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       //$current_link =end( explode( "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]","/" ));
      if(isset($_GET[$subject])){
        $current = $subject.'='.$_GET[$subject];
        $replace = $subject.'='.$replace;
        return str_replace($current,$replace,$current_link );
      }
      else if(count($_GET) == 0){
        $current_link = $current_link."?".$subject."=".$replace;
      }
      else{
        $current_link = $current_link."&".$subject."=".$replace;;
      }
      
      return $current_link ;
   }
  }
 ?>