<?php
// INTERFACE DE PARAMETRAGE DE LA CAMPAGNE AVEC SELECTION DES CLIENTS ET DE LA DATE ET DEFINITION DU MESSAGE
// AVEC IMPORTATION VIRTUELLES DES DONNEES PERSONNELLES DANS LE MESSAGE
// initialisation des variables
$sommeclefs=0;
$credits=0;
$message='';
$mysql_link=null;
$voir=null;
$pseudo=null;
$mdp=null;
$idclient=0;
$select="";
$selectParams="";
$nbParams=0;
$i=0;
$c=0;
$data=null;
$resultat=false;
$fichier='';
$num=0;
$rows=0;
$mobile=0;
$nom=0;
$prenom=0;
$adresse1=0;
$adresse2=0;
$ville="";
$postal=0;
$pseudonyme=0;
$password=0;   //L30
$email=0;
$TabCampagne=Array();
$TabNom=Array();
$TabPreNom=Array();
$TabAdresse1=Array();
$TabAdresse2=Array();
$TabVille=Array();
$TabPseudo=Array();
$TabPassword=Array();
$TabEmail=Array();
$ChampManquant=Array();
$NbCarRow=0;
$NbCarMax=0;

$message=$_REQUEST['textemessage'];
$codesession=$_REQUEST['codesession'];
$campagne=$_REQUEST['campagne'];
// verification de la connection par la session
/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe";
$url_ftp="http://sms.web-diffusion-france.com/campagnesbtoc/"
/*****************************************************************/


// L 50
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/


$requete = "SELECT * FROM session WHERE codesession=$codesession;";

$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysqli_error($mysql_link); return;}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); return;}

$idclient=$voir['idclient'];

if($idclient != null)	{
//identifier client par rapport a son pseudo et mdp;
$requete = "SELECT * FROM utilisateurs WHERE idclient=$idclient;";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{  // L 40
echo "warning : impossible  de vous identifier:Faites retour";
echo " puis recommmencer: ".mysqli_error($mysql_link);
	exit();
	 }
	$voir = mysqli_fetch_array($result);
	$credits=$voir['credits'];
		} // if($pseudo!= null)
// gestion de la campagne eventuellement selectionnée
//L80
$requete = "SELECT * FROM campagne WHERE idclient=$idclient and nom_campagne='$campagne'";
$result = mysqli_query($mysql_link,$requete);
if($result != null)	{
// campagnes actives

	$voir=mysqli_fetch_array($result);
	$fichier = $voir['fichier'];
	$mobile  = $voir['mobile'];
	$nom=$voir['nom'];
	$prenom=$voir['prenom'];
	$adresse1=$voir['adresse1'];
	$adresse2=$voir['adresse2'];
	$ville=$voir['ville'];
	$postal=$voir['postal'];
	$pseudonyme=$voir['pseudonyme'];
	$password=$voir['password'];
	$email=$voir['email'];

	$selectParams="<select name=\"parametres\" multiple size=9 onDblClick=\"ajouteParam(this.options[this.selectedIndex]);\">";
	if($nom !=0){$selectParams .="<option value=\"$nom\">nom";$nbParams++;};
	if($prenom !=0){$selectParams .="<option value=\"$prenom\">prenom";$nbParams++;};
	if($adresse1 !=0){$selectParams .="<option value=\"$adresse1\">adresse1";$nbParams++;};
	if($adresse2 !=0){$selectParams .="<option value=\"$adresse2\">adresse2";$nbParams++;};
	if($ville !=0){$selectParams .="<option value=\"$ville\">ville";$nbParams++;};
	if($postal !=0){$selectParams .="<option value=\"$postal\">postal";$nbParams++;};
	if($pseudonyme !=0){$selectParams .="<option value=\"$pseudonyme\">pseudonyme";$nbParams++;};
	if($password !=0){$selectParams .="<option value=\"$password\">password";$nbParams++;};
	if($email !=0){$selectParams .="<option value=\"$email\">email";$nbParams++;};
 	$selectParams .="</select>";

			} // if result != null
echo "<html><head>";
echo "<script language='Javascript'>\n";
echo " var wdf=window.open(\"\",\"\",\"statusbar=0,toolbar=0,menubar=0,scrollbars=yes\");\n";
echo " var doc=wdf.document;\n";
echo " doc.writeln(\"Recuperation fichier de mobiles...\");";
//$handle = fopen($url_ftp.$fichier, "r");
$handle = fopen("../campagnesbtoc/".$fichier, "r");
if(($data = fgetcsv($handle, 500, ";")) != FALSE)
	{ //120
echo " doc.writeln(\"...OK\");";
$num = count($data);
	}else 	{
echo " doc.writeln(\"...Impossible recuperer fichier de données\");";
exit();
		}


echo "doc.writeln(\"<BR>identification colonne mobile...\");";
for ($c=0,$j=1; $c < $num; $c++,$j++){
if($j==$mobile && !is_numeric($data[$c]))	{
echo " doc.writeln(\"La colonne mobile du fichier uploadé ne colle pas avec le rang de la variable specifiée<BR>\");";
echo " doc.writeln(\"dans le parametrage de la campagne\");";
exit();
						}
if($j==$mobile && is_numeric($data[$c]))break;

				     } // fin for.
echo " doc.writeln(\"...OK\");";
//  definition du select des params L 140

echo " doc.close();";
//echo " wdf.resizeTo(600,500);wdf.moveTo(200,200)\");\n";
echo "</script>";

//$rows=0;
	$listeclients= "<p align=center style='border:solid;border-color:red;border-width:1'>";
	$listeclients.= "<table border=1 width=40% cellspacing=0 cellpading=0 style='font-size:10;font-family:courrier' >";
	$listeclients.= "<tr><td colspan=4 align=center>Liste des clients :cliquez sur ceux que vous voulez selectionner.<BR>";
	$listeclients.= " ...ou sur la derniere case en bas(tout selectionner).";
	$listeclients.= "</TD></TR>";
	
do{
$num = count($data);
$select="popup(this,";
//slection des params a fficher dans le popup
if($num > 1){

if($nom >0){$select .="\"nom:".$data[($nom -1)]."\",";$TabNom[$rows]=strlen($data[($nom -1)]);}
if($prenom > 0){$select .="\"prenom:".$data[($prenom -1)]."\",";$TabPreNom[$rows]=strlen($data[($prenom -1)]);}
if($adresse1 > 0){$select .="\"adresse1:".$data[($adresse1 -1)]."\",";$TabAdresse1[$rows]=strlen($data[($adresse1 -1)]);}
if($adresse2 > 0){$select .="\"adresse2:".$data[($adresse2 -1)]."\",";$TabAdresse2[$rows]=strlen($data[($adresse2 -1)]);}
if($ville > 0){$select .="\"ville:".$data[($ville -1)]."\",";$TabVille[$rows]=strlen($data[($ville -1)]);}
if($postal > 0)$select .="\"postal:".$data[($postal -1)]."\",";
if($pseudonyme > 0){$select .="\"pseudonyme:".$data[($pseudonyme -1)]."\",";$TabPseudo[$rows]=strlen($data[($pseudonyme -1)]);}
if($email > 0){$select .="\"email:".$data[($email -1)]."\",";$TabEmail[$rows]=strlen($data[($email -1)]);}
if($password > 0){$select .="\"password:".$data[($password -1)]."\",";$TabPassword[$rows]=strlen($data[($password -1)]);}
if($mobile > 0)$select .="\"mobile:".$data[($mobile - 1)]."\"";

$select .=");";

}   //L170
$listeclients.= "<TR><TD><input type=checkbox id=$rows onClick='$select'></TD>";
	// affichage des enregistrement avec choix possibles
for ($c=0,$j=1; $c < $num;$c++,$j++){
	if($j==$nom || $j==$prenom || $j==$pseudonyme || $j == $email)
	$listeclients.= "<TD>$data[$c]</TD>";
}
	$listeclients.= "</TR>";
$rows++;
	} while(($data = fgetcsv($handle, 500, ";")) != FALSE);
	$listeclients.= " <TR><TD colspan=4 align=left>...<input id='box' type=checkbox value=0 onClick='toutSelectionner($rows)'></TD></TR>";
	$listeclients.="</table>";


/*

if(is_array($TabNom) && count($TabNom) > 100)
 print_r("nb valeur de TabNom".count($TabNom));
else if(!is_array($TabNom))echo("Tabnom pas tablo<BR>");

if(is_array($TabPreNom))
 print_r("nb valeur de TabPreNom".count($TabNom));
else if(!is_array($TabPreNom))echo("TabPreNom pas tablo<BR>");


if(is_array($TabAdresse1))
 print_r("nb valeur de TabAdresse1".count($TabAdresse1));
else if(!is_array($TabAdresse1))echo("TabAdresse1 pas tablo<BR>");

if(is_array($TabAdresse2))
 print_r("nb valeur de TabAdresse2".count($TabAdresse2));
else if(!is_array($TabAdresse2))echo("TabAdresse2 pas tablo<BR>");

if(is_array($TabVille))
 print_r("nb valeur de TabVille".count($TabVille));
else if(!is_array($TabVille))echo("TabVille pas tablo<BR>");

if(is_array($TabPseudo))
 print_r("nb valeur de TabPseudo".count($TabPseudo));
else if(!is_array($TabPseudo))echo("TabPseudo pas tablo<BR>");

if(is_array($TabPassword))
 print_r("nb valeur de TabPassword".count($TabPassword));
else if(!is_array($TabPassword))echo("TabPassword pas tablo<BR>");

if(is_array($TabEmail))
 print_r("nb valeur de TabEmail".count($TabEmail));
else if(!is_array($TabEmail))echo("TabEmail pas tablo<BR>");

*/ //L230



for($j=0 ; $j < $rows ; $j++){
if(count($TabPseudo)==$rows && count($TabPreNom)==$rows && count($TabNom)==$rows && count($TabAdresse2)==$rows && count($TabAdresse1)==$rows && count($TabVille)==$rows &&  count($TabPassword)==$rows && count($TabEmail)==$rows){
$NbCarRow=$TabNom[$j]+$TabPreNom[$j]+$TabAdresse1[$j]+$TabAdresse2[$j]+$TabVille[$j]+$TabPseudo[$j]+$TabPassword[$j]+$TabEmail[$j]+5;
}

if($NbCarMax < $NbCarRow)$NbCarMax=$NbCarRow;
			} // fin for


$TabDate=getDate();
$chaineDate=$TabDate['year']."-".$TabDate['mon']."-".$TabDate['mday'];
// ." ".$TabDate['hours'].":".$TabDate['minutes'].":".$TabDate['seconds'];
mysqli_close($mysql_link);
?>

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
<script Language=Javascript src="../jscripts/menu.js"></script>
</head>
<body  bgcolor='lightyellow' onload='initialiseTabRows(<?php echo $rows.",".$NbCarMax; ?>)'>
<script Language="Javascript">Menu(<?php echo($codesession); ?>);</script>
<P>&nbsp;</P>
<P>
<?php echo $listeclients;?>
</P>

<p align=center>
<input type=text size=4 value="<?php echo $credits;?>" disabled> credits restants
<form name="envoisms" method="POST"  action="./EnvoiSMSCampPerso.php?">
<input type=hidden name=codesession value=<?php echo "$codesession";?> >
<input type=hidden name=champsRowsInclus>
<input type=hidden name=campagne value=<?php echo $campagne; ?> >
<table width=100% align=center border=0 cellpading=5 cols=3  bgcolor="lightyellow">

<tr><td bgcolor="cyan" colspan=3 align=center>
<font size=5 face="Times New Roman" color="black">Paramétrage  de la Campagne du <?php echo $chaineDate; ?></font>
</TD></tr>
<TR><TD align=left>
<font size=3 face="Times New Roman" color="navy">
votre m&eacute;ssage </font> :&nbsp;&nbsp;&nbsp;
<textarea cols=30 rows=4 id="textemessage" name="textemessage" onKeyPress='addelDigit()'>
</textarea></TD>
<td align=right>

<?php if($nbParams !=0)echo "$selectParams";?>
</TD>
</TR>

<tr><td colspan=3 align=center>
<input type=text name="compteur" value=160  size=2 disabled>
<font weight="bold" size=3 face="Times New Roman" color="navy">Caractères restants</font>
</td></tr>
<tr>
<TD colspan=3 align=left> 
... et donc si vous voulez programmer son declenchement :<BR>
<input name=flagprogcamp type=checkbox onClick='afficheChampsDate()' value=true>
<div id="groupedate"  style='visibility:"hidden";border:solid;border-color:"blue";border-width:1'>
(un clic affiche son info actuelle dans le champs voulu)<BR>
<input type=text size=2 maxlength=2 name=annee onClick='afficheDate(this,"annee")'> Année
<input type=text size=2 maxlength=2 name=mois onClick='afficheDate(this,"mois")'> Mois
<input type=text size=2 maxlength=2 name=jour onClick='afficheDate(this,"jour")'> Jour<BR>
<input type=text size=2 maxlength=2 name=heure onClick='afficheDate(this,"heure")'> Heure
<input type=text size=2 maxlength=2 name=minutes onClick='afficheDate(this,"minutes")'> Minutes<BR>
<input type=button name=bplus value="+" onClick='incrementeHeure(true)'><input type=button name=bmoins value="-" onClick='incrementeHeure(false)'></div>
</TD>
</TR><TR>
<td  align=center><input type=button value="envoyer" onClick='VerifSubmitCampPerso()'></TD></TR>
</form>
<td align=center>
<a href="http://web-diffusion-france.com/ventes-sms/paiements.html"> <font size=4 face="Times New Roman" color="navy" weight="bold">Cliquez ici pour approvisionner votre compte en sms</font></A>
</TD></tr>

<TR><td colspan=3>&nbsp;</TD></TR>

<tr><td align=right colspan=3>
<a target=_blank href="http://web-diffusion-france.com"><font size=2 face="Times New Roman" color="navy" weight="bold">Services web-diffusion-france</font></A></TD></TR>
</table>


</body>
</html>