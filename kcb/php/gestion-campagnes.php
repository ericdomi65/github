<?PHP
/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/



$codesession=$_REQUEST['codesession'];
$voir=null;
$fp=null;
$i=0;
$j=0;
$m=0;
$tableauMobiles=array();
$tableaumessages=array();
$tableauFichiers=array();
$TabHCampagne=array();
$table=" ";
$tablemsg=" ";
$mobile=0;
$ref_liste=0;
$libelle="";
$statut="";
$resultat_histo_camp=false;
$selectHC="";
$reqFlag=0;
/* L20 */
$motClef="";
$motclef = empty($_REQUEST['motclef'])? "" : $_REQUEST['motclef'];
$datedeb= empty($_REQUEST['datedeb'])? "" : $_REQUEST['datedeb'];
$datefin = empty($_REQUEST['datefin'])? "" : $_REQUEST['datefin'];
$ref_liste= empty($_REQUEST['ref_liste'])? "" : $_REQUEST['ref_liste'];
$campagne=empty($_REQUEST['campagne'])? "" : $_REQUEST['campagne'];

// L26
/*****************************************************************/
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/

$requete = "SELECT * FROM session WHERE codesession=$codesession;";
$result = mysqli_query($mysql_link,$requete);
if(!$result){echo "Erreur 1 impossible identifier la session: codesession= $codesession".mysqli_error($mysql_link); exit();}

$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); exit();}
$idclient=$voir['idclient'];
// recherche des resultats de campagnes envoyées.
$requete = "SELECT * FROM histocamp WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);
if($result != null){
// campagnes actives
	$resultat_histo_camp=true;
	$numrows=mysqli_num_rows($result);
//43
	$selectHC= "<select  name=\"histo_campagne\" onchange=\"this.selectedIndex > 0? afficheDetailsHCmpgn(this.options[this.selectedIndex].value):void(0)\">";
	$selectHC .="<option value=\"aucune\">aucune campagne \n";
$m=0;
	while ($m < $numrows)	{
$voir = mysqli_fetch_array($result);
$TabHCampagne[$m]=array();

$TabHCampagne[$m][0]=$voir['nom_campagne'];
$TabHCampagne[$m][1]=$voir['date_campagne'];
$TabHCampagne[$m][2]=$voir['nbmobiles'];
$TabHCampagne[$m][3]=$voir['nbsms'];
$TabHCampagne[$m][4]=$voir['EXPIRED'];
$TabHCampagne[$m][5]=$voir['FAILED'];
$TabHCampagne[$m][6]=$voir['UNKNOWN'];
$TabHCampagne[$m][7]=$voir['QUEUED'];
$TabHCampagne[$m][8]=$voir['clef'];
$TabHCampagne[$m][9]=$voir['ref_liste'];
$TabHCampagne[$m][10]=$voir['motclef'];
if($motClef==null)	{
$motClef=$voir['motclef'];$TabHCampagne[$m][11]=2;
			}
else	{ 
if($motClef==$voir['motclef']) $TabHCampagne[$m][11]=true;
	else { $TabHCampagne[$m][11]=2;$motClef=$voir['motclef'];}
	}
$selectHC .="<option value=$m>".$TabHCampagne[$m][0]."-".$TabHCampagne[$m][1]."\n";
$m++;

				} // fin while L69
	$selectHC .="</select>";

	
		} // if result != null

if(!empty($datedeb)){
if(!empty($datefin))$condition="AND message.date_msg <='$datefin'";else $condition="";
if(!empty($ref_liste) && $ref_liste!=0)	{
// cas selection de campagne dans liste (liste de mobiles aléatoire)
$requete = "SELECT *,message.clef msgclef  FROM mobile_random mobile,mesg_prosp message WHERE mobile.idclient=$idclient AND mobile.ref_liste=$ref_liste AND mobile.mobile=message.mobile AND message.date_msg >='$datedeb'  $condition AND message.motclef='$motclef'   ;";
$reqFlag=2;
				}
else	{
// campagne sous fichier
$requete = "SELECT *,message.clef msgclef  FROM mesg_prosp message WHERE  message.date_msg >='$datedeb' $condition AND message.motclef='$motclef' AND message.idclient=$idclient;";
$reqFlag=3;
	}

		} // if(!empty($datedeb))
else 	{
// au chargement depuis menu (toutes listes confondues)
$requete = "SELECT *,message.clef msgclef  FROM mobile_random mobile,mesg_prosp message WHERE mobile.idclient=$idclient AND mobile.mobile=message.mobile ORDER BY ref_liste,motclef ASC;";
$reqFlag=1;

	}
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{  
echo "warning : impossible  de telecharger messages retour campagne : ".mysqli_error($mysql_link." reqFlag = ".$reqFlag);
	exit(); 
	}
	$numrows=mysqli_num_rows($result);
	$i=0;


// 3 cas fonctionnels relatifs aux cas où soit il y a selection d'une campagne avec liste de mobiles, soit pas de liste(fichier),soit c'est un premier chargement(toutes listes confondues)
if($reqFlag==1){	// cas chargement depuis menu (toutes listes confondues)
	$voir = mysqli_fetch_array($result);
	$ref_liste=$voir['ref_liste'];
	$libelle = $voir['libelle'];
//initialisat° tablo fichiers
$tableauFichiers[0]=$ref_liste."_".$libelle.".csv";

// initialisation de la table
$table="<table width=100% border=1 align=center cellpading=10><TR><TH>Reference liste</TH><TH>Libelle liste</TH><TH>Mobile</TH>";
$table.="<TH>statut reponse serveur</TH><TH>Observations</TH></TR>";
$table .="<tr><TD align=center valign=middle colspan=5><A HREF=\"../../fichier-campagne/$tableauFichiers[0]\">telecharger fichier $ref_liste-$libelle.csv</A></TD></TR>";
//initialisation du fichier csv: L43
$fp = fopen("../fichier-campagne/".$ref_liste."_".$libelle.".csv", 'w');

	while($i < $numrows){
//		if(!$voir)
	$voir = mysqli_fetch_array($result);

	if($ref_liste != $voir['ref_liste']){
		fclose($fp);
		$ref_liste = $voir['ref_liste'];
		$libelle = $voir['libelle'];
$tableauFichiers[$i]=$ref_liste."_".$libelle.".csv";
$table .="<tr><TD align=center valign=middle colspan=5><A HREF=\"../../fichier-campagne/$tableauFichiers[$i]\">telecharger fichier $ref_liste-$libelle.csv</A></TD></TR>";
$fp = fopen("../fichier-campagne/".$ref_liste."_".$libelle.".csv", 'w');
$tableaumessages=array();
$j=0;

			} // fin if($ref_liste != $voir['ref_liste']) L66

	$clef=$voir['msgclef'];
	$ref_liste=$voir['ref_liste'];
	$libelle = $voir['libelle'];
	$mobile=$voir['mobile'];
	$statut = $voir['neglctd'] >0?"neglctd":$voir['expired'] >0?"expired":$voir['failed'] >0?"failed":"unknown";
	$datmsg=$voir['date_msg'];
	$motclef=$voir['motclef'];
	$message=$voir['message'];

	$tableaumessages[$j][0]=$mobile;
	$tableaumessages[$j][1]=$datmsg;
	$tableaumessages[$j][2]=$motclef;
	$tableaumessages[$j][3]=$message;




$table .="<tr><TD align=center valign=middle>ref liste: $ref_liste</TD><TD>$libelle</TD><TD align=center><input type=checkbox onClick='ajouteMobile($ref_liste,\"$libelle\",$mobile)'>$mobile</TD><TD>$statut</TD><TD>\n";
$tablemsg ="<table border=1><TR><TH>date msg</TH><TH>mot clef</TH><TH>message</TH></TR>";
$tablemsg .="<TR><TD>$datmsg</TD><TD>$motclef</TD><TD>$message</TD><TD><A HREF=\"Javascript:void(0)\" onClick='supprimerMsg($clef)'>supprimer message</A></TD></TR></table>";
$table .=$tablemsg."</TD></TR>";

		$voir=null; //L100
		$i++;$j++;
				} // fin while;
		if (flock($fp, LOCK_EX)) 
	{ // pose un verrou exclusif
	for($j=0; $j < count($tableaumessages); $j++)
		{
		fputcsv($fp,$tableaumessages[$j],";");
		}
flock($fp, LOCK_UN); // libère le verrou
//echo("<BR>fichier csv crée et renseigné.<BR>");

	}else {echo(" <BR>probleme ecriture fichier.<BR>");exit();}
fclose($fp);




$table .="</table><BR>Pour les cases cochees : <A HREF='Javascript:void(0)' onClick='supprimerMobiles()'><font color=red>[X]</font> Supprimer mobiles</A>";
	} // fin if reqflag==1

if($reqFlag==2)	{
// cas selection de campagne dans liste 

	$voir = mysqli_fetch_array($result);
	$ref_liste=$voir['ref_liste'];
	$libelle = $voir['libelle'];
//initialisat° tablo fichiers
$tableauFichiers[0]=$ref_liste."_".$libelle.".csv";

// initialisation de la table
$table="<table width=100% border=1 align=center cellpading=10><TR><TH>Reference liste</TH><TH>Libelle liste</TH><TH>Mobile</TH>";
$table.="<TH>statut reponse serveur</TH><TH>Observations</TH></TR>";
$table .="<tr><TD align=center valign=middle colspan=5><A HREF=\"../../fichier-campagne/$tableauFichiers[0]\">telecharger fichier $ref_liste-$libelle.csv</A></TD></TR>";
//initialisation du fichier csv: L43
$fp = fopen("../fichier-campagne/".$ref_liste."_".$libelle.".csv", 'w');

	while($i < $numrows){
		if(!$voir)	$voir = mysqli_fetch_array($result);
	$clef=$voir['msgclef'];
	$ref_liste=$voir['ref_liste'];
	$libelle = $voir['libelle'];
	$mobile=$voir['mobile'];
	$statut = $voir['neglctd'] >0?"neglctd":$voir['expired'] >0?"expired":$voir['failed'] >0?"failed":"unknown";
	$datmsg=$voir['date_msg'];
	$motclef=$voir['motclef'];
	$message=$voir['message'];

	$tableaumessages[$j][0]=$mobile;
	$tableaumessages[$j][1]=$datmsg;
	$tableaumessages[$j][2]=$motclef;
	$tableaumessages[$j][3]=$message;


$table .="<tr><TD align=center valign=middle>ref liste: $ref_liste</TD><TD>$libelle</TD><TD>$mobile</TD><TD>$statut</TD><TD>";
$tablemsg ="<table border=1><TR><TH>date msg</TH><TH>mot clef</TH><TH>message</TH></TR>";
$tablemsg .="<TR><TD>$datmsg</TD><TD>$motclef</TD><TD>$message</TD><TD><A HREF=\"Javascript:void(0)\" onClick='supprimerMsg($clef)'>supprimer message</A></TD></TR></table>";
$table .=$tablemsg."</TD></TR>";
		$i++;$j++;
		$voir=null;
			} // fin while;
$table .="</table>";
if (flock($fp, LOCK_EX)) 
	{ // pose un verrou exclusif
	for($j=0; $j < count($tableaumessages); $j++)
		{
		fputcsv($fp,$tableaumessages[$j],";");
		}
flock($fp, LOCK_UN); // libère le verrou
//echo("<BR>fichier csv crée et renseigné.<BR>");

	}else {echo(" <BR>probleme ecriture fichier.<BR>");exit();}
fclose($fp);

		} // fin if($reqFlag==2)

if($reqFlag==3)	{
// cas selection de campagne sous fichier
//initialisat° tablo fichiers

$table="<table width=100% border=1 align=center cellpading=10><TR><TH>Libelle campagne</TH><TH>Mobile</TH>";
$table.="<TH>statut reponse serveur</TH><TH>Observations</TH></TR>";
$table .="<tr><TD align=center valign=middle colspan=4><A HREF=\"../../fichier-campagne/$campagne.csv\">telecharger fichier $campagne.csv</A></TD></TR>";
//initialisation du fichier csv: L43
$fp = fopen("../fichier-campagne/".$campagne.".csv", 'w');

	while($i < $numrows){
	$voir = mysqli_fetch_array($result);
	$clef=$voir['msgclef'];
//	$ref_liste=$voir['ref_liste'];
//	$libelle = $voir['libelle'];
	$mobile=$voir['mobile'];
	$statut = !empty($voir['neglctd'])?"neglctd":!empty($voir['expired']) ?"expired":!empty($voir['failed']) ?"failed":"unknown";   //L 251
	$datmsg=$voir['date_msg'];
	$motclef=$voir['motclef'];
	$message=$voir['message'];

	$tableaumessages[$j][0]=$mobile;
	$tableaumessages[$j][1]=$datmsg;
	$tableaumessages[$j][2]=$motclef;
	$tableaumessages[$j][3]=$message;


$table .="<tr><TD align=center valign=middle>$campagne</TD><TD>$mobile</TD><TD>$statut</TD><TD>";
$tablemsg ="<table border=1><TR><TH>date msg</TH><TH>mot clef</TH><TH>message</TH></TR>";
$tablemsg .="<TR><TD>$datmsg</TD><TD>$motclef</TD><TD>$message</TD><TD><A HREF=\"Javascript:void(0)\" onClick='supprimerMsg($clef)'>supprimer message</A></TD></TR></table>";
$table .=$tablemsg."</TD></TR>";
		$i++;$j++;

			} // fin while;
$table .="</table>";
if (flock($fp, LOCK_EX)) 
	{ // pose un verrou exclusif
	for($j=0; $j < count($tableaumessages); $j++)
		{
		fputcsv($fp,$tableaumessages[$j],";");
		}
flock($fp, LOCK_UN); // libère le verrou
//echo("<BR>fichier csv crée et renseigné.<BR>");

	}else {echo(" <BR>probleme ecriture fichier.<BR>");exit();}
fclose($fp);
		} // fin if reqFlag==3
mysqli_close($mysql_link);
?>

<html><head>
<script Language="Javascript" src="../jscripts/listes-mobiles.js"></script>
<script Language="Javascript" src="../jscripts/menu.js"></script>
<script Language="Javascript" src="../jscripts/source-sms.js"></script>
 <script Language="Javascript">
function histo_Campagnes(num_C,nom_camp,date_camp,nbmobiles,nbsms,expired,failed,unknown,queued,ref_liste,motclef,flag){

this.num_C=num_C;
this.nom=nom_camp;
this.date=date_camp;
this.nbmobiles=nbmobiles;
this.nbsms=nbsms;
this.expired=expired;
this.failed=failed;
this.unknown=unknown;
this.queued=queued;
this.ref_liste=ref_liste;
this.motclef=motclef;
this.flag=flag;
}

<?php
if($resultat_histo_camp)
 for($x=0; $x < $m ;$x++){

echo "histo_Campagnes[".$x."]=new histo_Campagnes('".$TabHCampagne[$x][8]."','".$TabHCampagne[$x][0]."','";
echo $TabHCampagne[$x][1]."','".$TabHCampagne[$x][2]."','".$TabHCampagne[$x][3]."','".$TabHCampagne[$x][4]."','";
echo $TabHCampagne[$x][5]."','".$TabHCampagne[$x][6]."','".$TabHCampagne[$x][7]."','".$TabHCampagne[$x][9]."','".$TabHCampagne[$x][10]."',".$TabHCampagne[$x][11].");\n";

}
?>
</script>
</head>
<body>
<script Language="Javascript">
Menu(<?php echo("$codesession");?>);
</script>
<P align=center>
<?php if($resultat_histo_camp) echo $selectHC; ?> Historique campagnes
</P>
<?php echo("$table"); ?>
<form action="./gestion-campagnes.php" method="POST" name=rafraichir>
<input type=hidden name=motclef value="">
<input type=hidden name=datedeb value="">
<input type=hidden name=datefin value="">
<input type=hidden name=ref_liste value="">
<input type=hidden name=campagne value="">
<input type=hidden name=codesession value=<?php echo("$codesession"); ?>>
</form>
<div id="info">
<form action="./supprimerMobiles.php" METHOD="POST" NAME="supprimer" ID="supprimer">
</form>
</div>
</body>
</html>