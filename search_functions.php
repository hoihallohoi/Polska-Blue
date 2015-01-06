<!-- Gemaakt door Richard Kooijker -->
<?php
//aanroepen zoekmenu
function zoeken_bar(){
echo '
<fieldset style="width:30%"><legend>Zoeken naar producten.</legend>
<form action="" method="GET">
<input type="text" name="query" />
<input type="submit" value="Search" />
</form>
</fieldset>
';
}
//reguleert de paginanummering en zorgt dat de zoekopdracht behouden blijft tussen pagina's
function paginaknop (){
$controle = array('page','query','zoekveld','volgorde','gesorteert_op');
foreach($_GET as $zoeken){
if(in_array($zoeken, $controle)){
echo "<form>"
."imput type='hidden' name='' "
. "</form>" ;
}
}
}
?>