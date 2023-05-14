<?php
// reconnaitre le client via pseudo et mdp

/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/




$credits=0;
$pseudo=null;
$mdp=null;
$i=0;

$m=0;
$resultat=false;
$resultatLM=false;
$select="";
$selectMC="";
$champspseudo=true;
$champsmdp=true;
$TabCampagne =array();
$selectLM="";
if(empty($_REQUEST['pseudo']))$champspseudo=false;
if(empty($_REQUEST['mdp']))$champsmdp=false;
// L 18
if(!$champspseudo && !$champsmdp) { echo "Vous devez remplir tous les champs S.V.P";return;}
else if(!$champspseudo) {echo "Vous devez remplir le champs Pseudonyme S.V.P";return;}
else if(!$champsmdp )   {echo "Vous devez remplir le champs mot de passe S.V.P";return;}

$pseudo=$_REQUEST['pseudo'];
$mdp=$_REQUEST['mdp'];
//25


if($champspseudo && $champsmdp ) {  // verification de l'adresse email puis unicite mdp et pseudo
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/


//L31

$requete = "SELECT * FROM identite WHERE pseudo='$pseudo' and passe='$mdp'";
$result = mysqli_query($mysql_link,$requete);

if($result==null)
	{
 echo "la base de données ne retourne aucun resultat. Veuillez Recommencer.<BR> en faisant retour arriere";exit();
	 }

	$numrows=mysqli_num_rows($result);
	if($numrows != 1){
		
		if($numrows == 0){
// procedure recouvrement identifiants 

	echo ("<html><head></head><body bgcolor='lightyellow'>");
	echo ("impossible de vous identifier par vos identifiants, Veuillez recommencer en faisant retour arriere<BR>");
	echo("<p align=center><form name=envoisms method=post action='http://sms.web-diffusion-france.com/php/envoiid.php'>");
	echo("<input type=text name='email' size=35 > Envoi d'identifiant : entrez adresse email du compte paypal<BR>");
	echo("<input type=submit value='envoyer'></form></body></html>");
	exit();
				}			

			} //	if($numrows != 1)
	else	{ // 1 cpt trouve en BDD
// RECUPERER IDENTIFIANT L57
		$voir=mysqli_fetch_array($result);
		$idclient=$voir['identite'];
//INTERROGATION ABONNEMENT

$requete = "SELECT TO_DAYS(NOW()) - TO_DAYS(date_abo) ecoule,duree,renouv FROM abonnement WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);

if($result==null)
	{
 echo "La base de données ne retourne aucun resultat. Veuillez Recommencer.<BR> en faisant retour arriere";exit();
	 }
		$voir=mysqli_fetch_array($result);
$ecoule=$voir['ecoule'];
$duree=$voir['duree'];
$renouv=$voir['renouv'];

if($renouv == 0 && ($duree * 30) <= $ecoule){
// signaler fin abo, proposer nouvel abo
echo(" Votre abonnement a pris fin. Nous vous proposons de vous r&eacute;abonner le cas &eacute;ch&eacute;ant pour conserver vos mots clef");
echo(" <BR>Ceux ci restent en effet a votre disposition pendant une semaine a l' &eacute;ch&eacute;ance de l'abonnement");
echo("<BR> Nous vous proposons plusieurs formules avec ou sans tacite reconduction de 1 mois &agrave; un an<BR>");
if (file_exists('../ventes-sms/paiementabonnement.html') )
		include('../ventes-sms/paiementabonnement.html');
//echo("<BR>ecoule=$ecoule");
//L82
	exit();
	
}

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
	if($credits <= 10) echo(" <BR>credits bientot épuises<BR>");
	if($credits ==0)
			{ 
	echo("<P align=center> credits épuises. vous pouvez en racheter via PAYPAL en cliquant ici :<BR> ");
	echo ("<a target=_blank href='./ventes-sms/paiements.html'>");
	echo ("	ACHAT DE CREDITS SMS</A>");
 	exit();
			} // fin if
//107
		} // fin else 1 cpt trouve en BDD
// creer la session
$codesession=mt_rand(1000000,100000000);
$aujourd=getDate();
$chaineDate=$aujourd["year"]."-".$aujourd["mon"]."-".$aujourd["mday"];
$chaineHeure=$aujourd["hours"].":".$aujourd["minutes"].":".$aujourd["seconds"];

$requete = "INSERT INTO session (idclient,codesession,datedebut,heuredebut)";
$requete.=" values($idclient,".$codesession.",'".$chaineDate."','".$chaineHeure."');";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)echo("Erreur enregistrement session:".mysqli_error($mysql_link));
// creation session.
//120


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



// voir les campagnes associées au cpt L144
$requete = "SELECT * FROM campagne WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);

if($result != null){

// campagnes actives L150
	
	$numrows=mysqli_num_rows($result);


	$select = "<select id=\"campagne\" name=\"campagne\" onchange=\"afficheCampEtModifAction(this.selectedIndex)\">";
	$select .="<option value=\"aucune\">aucune campagne \n";

	$selectLM = "<select id=\"campagneLM\" name=\"campagneLM\" onchange=\"this.selectedIndex >0 ? modifActionLM(true):modifActionLM(false)\">";
	$selectLM .="<option value=\"aucune\">aucune campagne \n";

	while ($i < $numrows)	{
$voir = mysqli_fetch_array($result);

$TabCampagne[$i]=array();

	if($voir['ref_liste'] == 0){

$resultat=true;
//$TabCampagne[$i]=array(0=>$voir['nom_campagne'],1=>$voir['fichier'],2=>$voir['mobile']);
$TabCampagne[$i][0] = $voir['nom_campagne'];
$TabCampagne[$i][1] = $voir['fichier'];
$TabCampagne[$i][2] = $voir['mobile'];

$select .="<option value=\"".$TabCampagne[$i][0]."\">".$TabCampagne[$i][0];
			}
else{
$resultatLM=true;
$TabCampagne[$i]=array(0=>$voir['nom_campagne'],1=>null,2=>null);

$selectLM .="<option value=\"".$TabCampagne[$i][0]."\">".$TabCampagne[$i][0];

	} // fin else refliste ==0
$i++;
// echo " et i= $i<BR>";
				} // fin while
	$select .="</select>";
	$selectLM .="</select>";
//	echo "sortie de while";
	
}// if($result != null){
mysqli_close($mysql_link);
}	// if($champspseudo && $champsmdp )
 // L187
?>

<html>
<!DOCTYPE HTML><html lang='fr'><head><meta charset="utf-8" />
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
<script Language="Javascript" src=".././jscripts/source-sms.js">
</script>
<script Language="Javascript">
TabCampagne=new Array();

<?php
if($resultat)
 for($x=0; $x < $i ;$x++){
echo ("TabCampagne[".$x."]=new Array();");

for($j=0;$j < 3 ; $j++)
echo ("TabCampagne[".$x."][".$j."]='".$TabCampagne[$x][$j]."';");

}
?>

</script>
<script Language=Javascript src="../jscripts/menu.js"></script>
</head><body bgcolor='lightyellow'>
<script Language="Javascript">Menu(<?php echo($codesession); ?>);</script>
<p align=center>
<input type=text name='credits' size=4 value=<?php echo("$credits"); ?> disabled> credits restants
<form name=envoisms method=post action="./envoisms.php">
<input type=hidden id='codesession' name='codesession' value=<?php echo($codesession);?> >
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
<input type=button id='BoutonCampPerso' value="personnaliser campagne/selectionner destinataires" onClick="changeActionOuvrePerso(<?php echo $codesession;?>);" disabled>
</TD>
<TD  colspan=3 align=left> 
... et donc si vous voulez programmer son declenchement :<BR>
<input name=flagprogcamp type=checkbox onClick='afficheChampsDate()' checked>
<div id="groupedate"  style='visibility:"hidden";border:solid;border-color:"blue";border-width:1'>
(un clic affiche son info actuelle dans le champs voulu)<BR>
<input type=text size=2 maxlength=2 name=annee onClick='afficheDate(this,"annee")'> Année
<input type=text size=2 maxlength=2 name=mois onClick='afficheDate(this,"mois")' > Mois
<input type=text size=2 maxlength=2 name=jour onClick='afficheDate(this,"jour")' > Jour
<input type=text size=2 maxlength=2 name=heure onClick='afficheDate(this,"heure")' > Heure
<input type=text size=2 maxlength=2 name=minutes onClick='afficheDate(this,"minutes")' > Minutes<BR>
<input type=button name=bplus value="+" onClick='incrementeHeure(true)'><input type=button name=bmoins value="-" onClick='incrementeHeure(false)'>
</div>
</TD>
</TR><TR>
<td align=center valign=top><input type=button value="envoyer" onClick='VerifSubmit()'></TD></TR>
</form>
</tr>

<tr><td align=right colspan=3>
<a target=_blank href="http://web-diffusion-france.com"><font size=2 face="Times New Roman" color="navy" weight="bold">Services web-diffusion-france</font></A></TD></TR>
</table>

</body>
</html>



