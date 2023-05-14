<?PHP
$keyword=$_POST['keyword'];
$renouv=$_POST['renouv'];
$codesession=$_POST['codesession'];
/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/



$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($mysql_link,$requete);; 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysql_error(); exit();}


$voir = mysql_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysql_error(); exit();}

$idclient=$voir['idclient'];

if($idclient != null)	{

$requete="UPDATE keywords SET dispo=0 WHERE keyword='$keyword'";

$result = mysqli_query($mysql_link,$requete);; 
if(!$result)echo "<BR>Erreur 1 impossible MAJ table keywords:".mysql_error()."<BR>";
// creation enreg dans abo avance

$requete = "INSERT INTO abo_avance (idclient,motclef,date_abo,duree,renouv,dispo) VALUES ($idclient,'$keyword','CURDATE()',1,$renouv,0)";

$result = mysqli_query($mysql_link,$requete);; 
if(!$result)	{
echo "<BR>Erreur 1 impossible INSERT enreg table abo_avance:".mysql_error()."<BR>Contacter 09.50.111.333 HB Veuillez nous excuser pour cet incident";
exit();
	}
}// fin 	if($idclient != null)	
mysql_close($mysql_link);
?>
<html>
<head></head>
<body>
<table border=0 cellpading=20px align=center style="border:solid;border-color:cyan;border-width:3px;background-color:green;width:320px;">
<TR><TD valign=middle>Deuxieme &eacute;tape : proceder au paiement via paypal;(vous devez avoir, soit une carte bleue, soit un compte paypal)</TD</TR></table>
<? if($renouv){ ?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="CGXJ5QJBBA9T2">
<table>
<tr><td><input type="hidden" name="on0" value="location 1 mot clef">location 1 mot clef</td></tr>
<tr><td><input type="hidden" name="on1" value="illimite sans engagement">illimite sans engagement</td></tr>
</table>
<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>


<? }else{ ?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="R86FKLWPM98QL">
<table>
<tr><td><input type="hidden" name="on0" value="location 1 mot clef 1 mois">location 1 mot clef 1 mois</td></tr>
<tr><td><input type="hidden" name="on1" value="sans renouvellement">sans renouvellement</td></tr>
</table>
<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>

<? } ?>
</body>
</html>