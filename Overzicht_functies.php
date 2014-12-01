<?php
class Overzicht_functies{
	//artikelen per pagina
	public static $app = 20;
        public static $velden = array('naam','soort','afmeting','aantal','prijs');
		//soorteering volgorde
		public static $sgve = array('ASC','DESC');
		
    function zoeken($page,$woord,$veld,$soorter,$volgorde ){
        $db = Connect::getInstance();
        $mysqli = $db->getConnection();
        $page = ($page-1)*self::$app;
        $veld = $this->valid_check($veld,self::$velden);
        $volgorde = $this->valid_check($volgorde,self::$sgve);
        $soorter = $this->valid_check($soorter,self::$velden);
        $query="SELECT id,naam,prijs,aantal,soort,afmeting FROM producten WHERE ".$veld." LIKE CONCAT('%', ?, '%') ORDER BY ".$soorter." ".$volgorde." LIMIT ? , ".self::$app ;
        $this->pages($veld,$woord);
        if($stmt = $mysqli->prepare($query)) {
                    $stmt->bind_param('si',$woord,$page);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($id,$naam,$prijs,$aantal,$soort,$afmetingen);
        }
        $table = "<table>";
        $table .= "<tr class='title_overzicht_table'>";
        foreach(self::$velden as $veldnaam){
            $table .= "<td><a href='".$this->sorteringlink($veldnaam)."'>naam</a></td>";
        }
        $table .="<td>aanpassen</td>"
                ."</tr>";
        while($stmt->fetch()){
            $table .= "<tr><td>".$naam."</td><td>".$soort."</td><td>".$afmetingen."</td><td>".$aantal."</td><td>".$prijs."</td><td><a href='./aanpassen?".$id."'>aanpassen<a></td></tr>";
        }
        $table .= "</table>";
        return $table;
    }
    
    
    function valid_check($veld ,array $velden){
        if(in_array($veld, $velden)){
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
       $query = "SELECT COUNT(ID) FROM producten WHERE ".$veld." LIKE CONCAT('%', ?, '%')";
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