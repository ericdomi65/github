<?php
/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"

/*****************************************************************/

$credits=0;
$i=0;
$m=0;
$resultat=false;
$select="";
$selectLM="";
$selectMC="";
$TabCampagne =null;
$motclef="";
$codesession=$_REQUEST['codesession'];
//21

$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));
$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysqli_error($mysql_link); return;}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); return;}

$idclient=$voir['idclient'];

// INTERROGATION UTILISATEURS
$requete = "SELECT * FROM utilisateurs WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);

if($result==null)
	{	// ERREUR 2
 echo "probleme technique sur login : Erreur 2 : Veuillez Recommencer.<BR> en faisant retour arriere";exit();
	 }

// verifier credit suffisant
		$voir=mysqli_fetch_array($result);
		$credits=$voir['credits'];
	if($credits <= 10) echo(" credits bientot épuises");
	if($credits ==0)
			{ 
	echo("<P align=center> credits épuises. vous pouvez en racheter via PAYPAL en cliquant ici :<BR> ");
	echo ("<a target=_blank href='http://sms.web-diffusion-france.com/ventes-sms/paiements.html'>");
	echo ("	ACHAT DE CREDITS SMS</A>");
 	exit();
			} // fin if
// creer liste de mot clef
//interrogation abonnement et abo_avance
$requete = "SELECT motclef  FROM abonnement WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);
$voir = mysqli_fetch_array($result);
$motclef=$voir['motclef'];


$requete = "SELECT motclef  FROM abo_avance WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);
	$numrows=mysqli_num_rows($result);
$selectMC="<select name=\"motclef\"><option value=\"".$motclef."\">".$motclef."</option>\n";
	while ($m < $numrows)	{
		$voir = mysqli_fetch_array($result);
$selectMC .="<option value=\"".$voir['motclef']."\">".$voir['motclef']."</option>\n";
$m++;
				}

$selectMC .="</select>";


// voir les campagnes associées au cpt
$requete = "SELECT * FROM campagne WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);

if($result != null){

// campagnes actives
	
	$numrows=mysqli_num_rows($result);

//95
	$select = "<select id=\"campagne\" name=\"campagne\" onchange=\"afficheCampEtModifAction(this.selectedIndex)\">";
	$select .="<option value=\"aucune\">aucune campagne \n";

	$selectLM = "<select id=\"campagneLM\" name=\"campagneLM\" onchange=\"this.selectedIndex >0 ? modifActionLM(true):modifActionLM(false)\">";
	$selectLM .="<option value=\"aucune\">aucune campagne \n";

	while ($i < $numrows)	{
$voir = mysqli_fetch_array($result);
$TabCampagne[$i][0] = $voir['nom_campagne'];
if($voir['ref_liste'] == 0){
$resultat=true;
$TabCampagne[$i][1] = $voir['fichier'];
$TabCampagne[$i][2] = $voir['mobile'];
$select .="<option value=\"".$TabCampagne[$i][0]."\">".$TabCampagne[$i][0]."\n";
			}
else{
$resultatLM=true;
$TabCampagne[$i]=array(0=>$voir['nom_campagne'],1=>null,2=>null);
$selectLM .="<option value=\"".$TabCampagne[$i][0]."\">".$TabCampagne[$i][0]."\n";

	} // fin else refliste ==0
$i++;
// echo " et i= $i<BR>";
				} // fin while
	$select .="</select>";
	$selectLM .="</select>";
	
}// if($result != null){
mysqli_close($mysql_link);
?>
<html><head>
<style>
.bouton{
font-size: 12pt;
font-family: sans-serif;
font-weight: bold;
border : solid;
border-width : 1;
border-color:red
}
</style>
<script Language="Javascript" src="../jscripts/source-sms.js">
</script>
<script Language="Javascript">
TabCampagne=new Array();

<?php
if($resultat)
 for($x=0; $x < $i ;$x++){
echo "TabCampagne[".$x."]=new Array();\n";
for($j=0;$j < 3 ; $j++){
echo "TabCampagne[".$x."][".$j."]='".$TabCampagne[$x][$j]."';";
echo "\n";
}
}
?>

</script>
<script Language=Javascript src="../jscripts/menu.js"></script>
</head><body bgcolor='lightyellow'>
<script Language="Javascript">Menu(<?php echo($codesession); ?>);</script>
<p align=center>
<input type=text name='credits' size=4 value=<?php echo("$credits"); ?> disabled> credits restants
<form name=envoisms method=post action="./envoisms.php">
<input type=hidden name=codesession value=<?php echo "$codesession";?> >
<table width=500 align=center border=0 cellpading=5 cols=3  bgcolor="lightyellow">

<tr><td width=100% bgcolor="cyan" align=center>
<font size=5 face="Times New Roman" color="black">Envoi de sms</font>
</TD></tr>

<TR><td colspan=3 align=left>
<font size=3 face="Times New Roman" color="navy">Num&eacute;ro destinataire :</font>&nbsp;&nbsp;&nbsp;
<input type=text size=10 maxlength=10 value="Destinataire" name="telephone" onFocus="this.value='';">
</TD></TR>

<TR><td colspan=3>&nbsp;</TD></TR>
<TR><TD align=left colspan=3 >
<font size=3 face="Times New Roman" color="navy">
votre m&eacute;ssage </font> :&nbsp;&nbsp;&nbsp;
<textarea cols=30 rows=4 id="textemessage" name="textemessage"  onKeyPress='addelDigit()'></textarea></TD></TR>

<tr><td colspan=3 align=center>
<input type=text name="compteur" value=160  size=2 disabled>
<font weight="bold" size=3 face="Times New Roman" color="navy">Caractères restants</font>
</td></tr>

<tr>
<TD valign=top>Eventuellement votre campagne sous fichier nominatif<BR>
<?php if($resultat) echo $select ?><BR>
Ou votre campagne sous liste aleatoire de mobiles<BR>
<?php if($resultatLM)echo $selectLM ?>
</TD>
<TD valign=top>Selectionner mot clef associe a votre eventuelle campagne(imperatif !)<BR>
<?php 
echo $selectMC;
?>
</TD>
<TD valign=top>
<input type=button id='BoutonCampPerso' value="personnaliser campagne/selectionner destinataires" onClick="changeActionOuvrePerso(<?php echo($codesession); ?>);" disabled>
</TD>
<TD  colspan=3 align=left> 
... et donc si vous voulez programmer son declenchement :<BR>
<input name=flagprogcamp type=checkbox onClick='afficheChampsDate()'>
<div id="groupedate"  style='visibility:"hidden";border:solid;border-color:"blue";border-width:1'>
(un clic affiche son info actuelle dans le champs voulu)<BR>
<input type=text size=2 maxlength=2 name=annee onClick='afficheDate(this,"annee")'> Année
<input type=text size=2 maxlength=2 name=mois onClick='afficheDate(this,"mois")' > Mois
<input type=text size=2 maxlength=2 name=jour onClick='afficheDate(this,"jour")' > Jour<BR>
<input type=text size=2 maxlength=2 name=heure onClick='afficheDate(this,"heure")' > Heure
<input type=text size=2 maxlength=2 name=minutes onClick='afficheDate(this,"minutes")' > Minutes<BR>
<input type=button name=bplus value="+" onClick='incrementeHeure(true)'><input type=button name=bmoins value="-" onClick='incrementeHeure(false)'>
</div>
</TD>
</TR><TR>
<td align=center valign=top><input type=button value="envoyer" onClick='VerifSubmit()'></TD></TR>
</form>
<td align=center>
<a href="http://sms.web-diffusion-france.com/ventes-sms/paiements.html"> <font size=4 face="Times New Roman" color="navy" weight="bold">Cliquez ici pour approvisionner votre compte en sms</font></A>
</TD></tr>

<tr><td align=right colspan=3>
<a target=_blank href="http://web-diffusion-france.com"><font size=2 face="Times New Roman" color="navy" weight="bold">Services web-diffusion-france</font></A></TD></TR>
</table>

</body>
</html>



