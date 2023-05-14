<?

/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"

/*****************************************************************/

$i=0;
$j=0;
$tableabo=null;
$tableaboavance=null;
$date_abo=null;
$duree=null;
$renouv=false;
$motclef="";
$idclient=0;
$listekeywords=null;
$codesession=$_REQUEST['codesession'];

$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));


$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($requete); 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysqli_error(); exit();}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error(); exit();}

$idclient=$voir['idclient'];

if($idclient != null)	{

$requete = "SELECT * FROM abonnement WHERE idclient=$idclient";

$result = mysqli_query($requete); 
if(!$result){echo "Erreur 1 impossible identifier abonnement:".mysql_error(); exit();}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2  impossible identifier abonnement::".mysql_error(); exit();}

$date_abo=$voir['date_abo']; // L40
$duree=$voir['duree'];
$renouv=$voir['renouv'];
$motclef=$voir['motclef'];

$renouv=$renouv? 'OUI':'NON';

$tableabo="<table align=center border=1 width=100%>";
$tableabo .="<TR><TD>Date abonnement : $date_abo</TD><TD> Duree abonnement : $duree</TD><TD>Renouvellement tacite :$renouv</td>";
$tableabo .="<TD>Mot clef : $motclef</TD></TR></table>";

$requete = "SELECT * FROM abo_avance WHERE idclient=$idclient AND dispo=1";

$result = mysqli_query($requete); 
if(!$result)echo "<BR>Erreur 1 impossible identifier abonnement:".mysql_error()."<BR>";
	$numrows=mysqli_num_rows($result);
	if($numrows > 0)$tableaboavance="<table align=center width=100% border=1 cellpading=5>";
	while($i < $numrows){
	
		$voir = mysqli_fetch_array($result);
		$motclef=$voir['motclef'];				
		$date_abo=$voir['date_abo'];
		$duree=$voir['duree']; // L60
		$renouv=$voir['renouv'];
		$renouv=$renouv? 'OUI':'NON';
$tableaboavance .="<TR><TD>$i ieme Mot clef : $motclef</TD><TD>Date abonnement : $date_abo</TD><TD>Duree abonnement : $duree</TD>";
$tableaboavance .="<TD>Renouvellement tacite : $renouv</TD>";
$tableaboavance .="<TD><A HREF='javascript:void(0)' onClick='makeAvailable($motclef)'><font color='red'>Desabonnement Mot clef</font></A>";
$tableaboavance .="(suprimer tacite reconduction)</TD></TR>";
	$i++;
		} //fin while
// telechargement des mots clefs dispo pour tout eventuel ajout de mot clef reservé

$requete = "SELECT * FROM keywords WHERE  dispo=1";

$result = mysqli_query($requete); 
if(!$result)echo "<BR>Erreur 3 impossible telécharger mots clefs:".mysql_error()."<BR>";
	$numrows=mysqli_num_rows($result);
	if($numrows > 0)$listekeywords="<select name=keyword><option value=\"aucun\">aucun</option>";
	while($j < $numrows){
	
		$voir = mysqli_fetch_array($result);
	$valeur=$voir['keyword'];
	$listekeywords .="<option value=\"$valeur\">$valeur</option>\n";
	$j++;
			} // fin while

	$listekeywords .="</select>";
} //if($idclient != null)	
mysqli_close($mysql_link);
?>
<html>
<head>
<script Language="Javascript" src="http://web-diffusion-france.com/webdiffusionfrance/jscripts/menu.js"></script>
</head>
<body>
<script Language="Javascript">
Menu(<? echo("$codesession");?>);</script>

<P align=center>
<? echo("$tableabo"); ?>
</P>

<P align=center>
<? echo("$tableaboavance"); ?>
</P>
<form action="http://web-diffusion-france.com/webdiffusionfrance/php/reservation-mc.php" method="post">
<input type=hidden name=codesession value=<? echo("$codesession");?>>
<table border=0 cellpading=20px align=center style="border:solid;border-color:cyan;border-width:3px;background-color:blue;width:420px;">
<TR><TD valign=middle>
<? echo("$listekeywords"); ?></TD><TD valign=middle>
<input type=radio name=renouv value="1" checked>Avec renouvellement tacite&nbsp;&nbsp;&nbsp;<input type=radio name=renouv value="0">Sans renouvellement tacite
</TD><TD valign=middle><input type=submit value="je reserve ce mot clef">
</form>
</TD></TR>
</table>
<P>
<table cellpading=20px align=center style="border:solid;border-color:cyan;border-width:3px;background-color:blue;width:60px;">
<TR><TD valign=middle>
<form action="http://web-diffusion-france.com/webdiffusionfrance/php/abonnement.php" method="post">
<input type=hidden name=codesession value=<? echo("$codesession");?>>
<input type=submit value="Actualiser table">
</form></TD></TR>
</table>
</P>
</body>
</html>