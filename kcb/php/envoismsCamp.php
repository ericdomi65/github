<?php 

/*
renseigner plus bas: L152
$Login="localhost:3306";
$Mdp="";
$Bdd="kcb_data_struct";
//fichier d'envoi de campagnes personnalisées (eventuellement)
$url="http://wdf.kiffecausebouge.com/campagnesbtoc/";
*/

$messageparametre='';
$message='';
$messag='';
$tel=0;
$credits=0; 
$mysql_link=null;
$voir=null;
$idclient=0;
$ref_liste=0;
$expire=false;
$efface=false;
$erreuroperateur=false;
$select="";
$selectLM="";
$selectMC="";
$i=0;
$c=0;
$m=0;
$nbcamp=0;//L30
$data=null; 
$resultat=false;
$resultatLM=false;
$fichier='';
$num=0;
$rows=0;
$mobile=0;
$Numdelivre=0;
$Numexpire=0;
$Numefface=0;
$Numerreurinconnue=0;
$Numerreuroperateur=0;
$Nummalformation=0;
$Numoperateurrejet=0;
$NumQueued=0;
$TabCampagne=array();
$TabStatutMobile=null;
$message=$_REQUEST['textemessage'];
$codesession=$_REQUEST['codesession'];
$campagne=$_REQUEST['campagne']; //L50
$campagneLM=$_REQUEST['campagneLM']; 
$host=$_SERVER['REMOTE_ADDR'];
$feu=true; 
// verification de la connection par la session

function envoiSMS($message,$mobile){

// construction de la requete
$requete = '{"sandbox":true,"messages":['; 

$taleau_json=array();
$param["source_address"]="wdf";
$param["destination_address"]="+33$mobile";  
$param["content"]="$message";

$taleau_json=json_encode($param);
$requete .=$taleau_json;
$requete .=']}';

//echo("requete envoi SMS = $requete");
 // url d'accès à la passerelle

$url = "https://api.tm4b.com/v1/sms";
 $ch = curl_init(); //parametres
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // L 100 retourne une variable  au lieu de l'afficher directement
curl_setopt($ch,CURLOPT_POST, 1); // active la méthode POST 
//L80
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: keep-alive','Content-Length: '.strlen($requete),'Content-Type: application/json', 'Accept: application/json','Api-Key: 724b54d63907b97c9545e10439bc50f6','Host '.$host));
curl_setopt($ch, CURLOPT_POSTFIELDS, $requete); // requete

// execute la connexion CURL

$reponse = curl_exec($ch);

// demande du statut de delivrance
curl_close($ch);

echo("<BR>travail sur la reponse...:<BR>");


$erreur=false;
$delivery_state="";
$requetteBdd="";

if (strlen($reponse) > 0) 	{
//echo("<BR>reponse = $reponse<BR>");
    $requetteBdd=json_decode($reponse, true); 


    if (!(json_last_error() == JSON_ERROR_NONE and is_array($requetteBdd)))
        {$requetteBdd='Données JSON invalides';$erreur=true;}
				} else
    {$requetteBdd='Aucune données JSON'; $erreur=true;}


//L110        

if(!$erreur && is_array($requetteBdd))	{

$delivery_state=$requetteBdd["messages"][0]["delivery_state"];
		}

/*
$Numexpire,$Numefface,$Numerreurinconnue,$Numdelivre 

*/
$reponse=$delivery_state;
echo("<BR>$reponse<BR>");
		//DELIVERED,EXPIRED,FAILED&REJECTED,ACCEPTED&QUEUED,UNKNOWN	
$TabStatutMobile=array(0=>array(),1=>array(),2=>array(),3=>array(),4=>array(),5=>array());

if($reponse=='ACCEPTED' || $reponse=='QUEUED' || $reponse=='DELIVERED' || $reponse== 'SENT'){
$Numdelivre +=1; $TabStatutMobile[0][count($TabStatutMobile[0])]=$mobile;$messag="SMS recu ou message en file d'attente au niveau opérateur mobile";
							}

else if($reponse=='EXPIRED'){$Numexpire +=1;$TabStatutMobile[1][count($TabStatutMobile[1])]=$mobile ;$messag="Erreur:Délais expiré"; }

else if($reponse=='FAILED')	{ 
$Numefface +=1;$TabStatutMobile[2][count($TabStatutMobile[2])]=$mobile ;$messag="Erreur:Message non délivré pour une ou plusieurs raisons (ex:destination invalide)";
			}

else if($reponse=='REJECTED')	{
 $Numefface +=1; $TabStatutMobile[3][count($TabStatutMobile[3])]=$mobile;$messag="Erreur : message ou numéro invalide";
			}
else if($reponse=='UNKNOWN'){$Numerreurinconnue +=1;$TabStatutMobile[4][count($TabStatutMobile[4])]=$mobile ;$messag="Erreur inconnue";}

}

 // fin fonction envoiSMS L140

echo "<html><head>";
if($campagneLM!="aucune" && $campagne != "aucune")	{
echo("<script>alert(\" Erreur : 2 campagnes ont étes selectionnées.\nRecommencez en selectionnant une campagne dans une seule liste\");</script>");
$feu=false;
						}
else if($campagneLM!="aucune")$campagne=$campagneLM;
else if($campagneLM=="aucune" && $campagne == "aucune")	{
echo("<script>alert(\" Erreur : aucune campagne n'a été selectionnée\");</script>");
$feu=false;

					}  
//L152
$mysql_link=mysqli_connect($Login, $Bdd,$Mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

if (!$mysql_link) 
  die("Connection failed: " . mysqli_connect_error());   

$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysqli_error($mysql_link); exit();}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); exit();}

$idclient=$voir['idclient'];

 //L136

//identifier client par rapport a son pseudo et mdp;
$requete = "SELECT * FROM utilisateurs WHERE idclient=$idclient;";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{  
echo "warning : impossible  de vous identifier:Faites retour";
echo " puis recommmencer: ".mysqli_error($mysql_link);
exit(); 	}
$voir = mysqli_fetch_array($result);
//L146
$credits=$voir['credits'];
if($credits <= 10 && $credit >= 1)	{
	echo("votre credit de sms est presque &eacute;puis&eacute;.<BR>");
	echo(" Vous pouvez en racheter via PAYPAL");	
				}
if($credits == 0)		{
	echo("votre credit de sms est &eacute;puis&eacute;.<BR> Vous pouvez en racheter via PAYPAL<BR>");
	echo "<a href='http://sms.web-diffusion-france.com/ventes-sms/paiements.html'>";
	echo "<font size=4 face='Times New Roman' color='navy' weight='bold'>";
	echo "Cliquez ici pour approvisionner votre compte en sms</font></A>";
	return; //L157
			}

	// voir les campagnes associées au cpt
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

//188
	$select = "<select id=\"campagne\" name=\"campagne\" onchange=\"afficheCampEtModifAction(this.selectedIndex)\">";
	$select .="<option value=\"aucune\">aucune campagne \n";
	$selectLM = "<select id=\"campagneLM\" name=\"campagneLM\" onchange=\"this.selectedIndex >0 ? modifActionLM(true):modifActionLM(false)\">";
	$selectLM .="<option value=\"aucune\">aucune campagne \n"; //L160
$i=0;
	while ($i < $numrows)	{
$voir = mysqli_fetch_array($result);
if($voir['ref_liste'] == 0){
$resultat=true;
$TabCampagne[$i]=array();
//L200

$TabCampagne[$i][0] = $voir['nom_campagne'];
$TabCampagne[$i][1] = $voir['fichier'];
$TabCampagne[$i][2] = $voir['mobile'];
$select .="<option value=\"".$TabCampagne[$i][0]."\">".$TabCampagne[$i][0]."\n";
$nbcamp++;
		  }
else{
$resultatLM=true;
$nom_campagne= $voir['nom_campagne'];
$selectLM .="<option value=\"".$nom_campagne."\">".$nom_campagne."\n";

	} // fin else refliste ==0
$i++;
			} // fin while
	$select .="</select>";
	$selectLM .="</select>"; 
//	echo "sortie de while";
	
	}// if($result != null)
echo "<script language='Javascript'>\n";
echo " var wdf=window.open(\"\",\"\",\"statusbar=0,toolbar=0,menubar=0,scrollbars=yes\");\n";
echo " var doc=wdf.document;\n";
//L221
// gestion de la campagne eventuellement selectionnée
if($feu){

$requete = "SELECT * FROM campagne WHERE idclient=$idclient AND nom_campagne='$campagne'";
$result = mysqli_query($mysql_link,$requete);
if($result != null)	{
// campagnes actives
$i=0;
	$voir=mysqli_fetch_array($result);
	$fichier = $voir['fichier'];
	$mobile  = $voir['mobile'];
	$ref_liste=$voir['ref_liste'];
  if(empty($message))
		{
$message=$voir['message'];

		}
			} // if result != null

// cas ou il y a un fichier csv de données a inclure dans le message
if($ref_liste==0){
$rows = 1;
echo " doc.writeln(\"Recuperation fichier de mobiles...\");";
//$handle = fopen("http://wdf.kiffecausebouge.com/campagnesbtoc/".$fichier, "r");
$handle = fopen("$url/campagnesbtoc/".$fichier, "r");
if(($data = fgetcsv($handle, 500, ";")) != FALSE)
	{ //L246
echo " doc.writeln(\"...OK\");";
$num = count($data);
	}else 	{
echo " doc.writeln(\"...Impossible recuperer fichier de données\");";
exit();
		}
echo "doc.writeln(\"<BR>identification colonne mobile...\");";
for ($c=0,$j=1; $c < $num; $c++,$j++){
if($j==$mobile && !is_numeric($data[$c]))	{
echo " doc.writeln(\"La colonne mobile du fichier uploadé ne colle pas avec le rang de la variable spécifiée<BR>\");";
echo " doc.writeln(\"dans le parametrage de la campagne\");";
exit();
						} //L259					
if($j==$mobile && is_numeric($data[$c]))break;
			         }
echo " doc.writeln(\"...OK\");";
do{	// debut de la boucle d'envoi
$messageparametre=$message;
for($i=0; $i < $num;$i++)	{
$j=$i+1;
if($j != $mobile && strripos($messageparametre,"[".$j."]"))
$messageparametre=str_replace("[".$j."]",$data[$i],$messageparametre);
			} // fin for L251
envoiSMS($messageparametre,$data[$c]);

$rows++;
//echo "doc.writeln(\"".$messageparametre."\");";

	} while(($data = fgetcsv($handle, 500, ";")) != FALSE);

} // fin recup fichier csv

if($ref_liste!=0){
// telechargement de la liste de mobiles aléatoires  L280
$requete="select mobile from mobile_random where idclient=$idclient and ref_liste=$ref_liste";
$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "Erreur telechargement liste mobiles:".mysqli_error($mysql_link);exit();}
	$numrows=mysqli_num_rows($result);
	while ($rows < $numrows)	{
	$voir = mysqli_fetch_array($result);
	$mobile = $voir['mobile'];
	envoiSMS($message,$mobile);		// Envois du message aux numéros mobiles
	$rows++;
				} // fin while
//insertion resultat dans mobile_random:
//L292
for($i=0; $i < count($TabStatutMobile[1]);$i++){
$requete="UPDATE mobile_random set invalide=1 WHERE idclient=$idclient AND mobile=$TabStatutMobile[1][$i]";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)echo "Erreur mise a jour statuts des mobiles:".mysqli_error($mysql_link);

}
for($i=0; $i < count($TabStatutMobile[2]);$i++)	{
$requete="UPDATE mobile_random set invalide=1 WHERE idclient=$idclient AND mobile=$TabStatutMobile[2][$i]";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)echo "Erreur mise a jour statuts des mobiles:".mysqli_error($mysql_link);
					}
//L304
for($i=0; $i < count($TabStatutMobile[3]);$i++)	{
$requete="UPDATE mobile_random set invalide=1 WHERE idclient=$idclient AND mobile=$TabStatutMobile[3][$i]";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)echo "Erreur mise a jour statuts des mobiles:".mysqli_error($mysql_link);
					}

for($i=0; $i < count($TabStatutMobile[4]);$i++)	{
$requete="UPDATE mobile_random set invalide=1 WHERE idclient=$idclient AND mobile=$TabStatutMobile[4][$i]";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)echo "Erreur mise a jour statuts des mobiles:".mysqli_error($mysql_link);
					}


} // fin if($ref_liste!=0)

//L320
echo "doc.writeln(\"...<BR>$rows messages envoyés. Terminé\");";
echo " doc.close();";
echo "</script>";

$TabDate=getDate();
$chaineDate=$TabDate['year']."-".$TabDate['mon']."-".$TabDate['mday']." ".$TabDate['hours'].":".$TabDate['minutes'].":".$TabDate['seconds'];



$requete = "INSERT INTO histocamp (idclient,nom_campagne,date_campagne,nbmobiles,expired,failed,unknown,queued) ";
$requete .= "values ($idclient,'$campagne','$chaineDate',$rows,$Numexpire,$Numefface,$Numerreurinconnue,$Numdelivre);";

$result = mysqli_query($mysql_link,$requete); 
if(!$result)echo "Erreur enregistrement stats campagne:".mysqli_error($mysql_link);

//identification du statut message puis decrementation credit eventuelle.

if($Numdelivre >0 || $NumQueued > 0)
	{

$credits -= ($Numdelivre + $NumQueued);
//L340
$requete = "update utilisateurs  set credits=$credits where idclient=$idclient;";
$result = mysqli_query($mysql_link,$requete);
if(!$result)echo "Erreur requete select sur ident:".mysqli_error($mysql_link);
mysqli_close($mysql_link);
	}
} // fin $feu==true;
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


<script Language="Javascript">
var TabCampagne=new Array();

<?php
if($resultat)
 for($x=0; $x < $nbcamp ;$x++){
echo " TabCampagne[".$x."]=new Array();\n";
for($j=0;$j <3 ; $j++){
echo "TabCampagne[".$x."][".$j."]='".$TabCampagne[$x][$j]."';";
echo "\n";
}
}
?>

</script>
<script Language="Javascript" src="../jscripts/source-sms.js"></script>
<script Language=Javascript src="../jscripts/menu.js"></script>
</head><body bgcolor='lightyellow'>

<script Language="Javascript">Menu(<?php echo($codesession); ?>);</script>
<p align=center>
<input type=text size=4 value=<?php echo $credits;?> disabled> credits restants

<form name=envoisms method="post"  action="./envoisms.php?">
<input type=hidden name=codesession value=<?php echo "$codesession";?> >

<table width=500 align=center border=0 cellpading=5 cols=3  bgcolor="lightyellow">

<tr><td width=100% bgcolor="cyan" colspan=3>
<font size=5 face="Times New Roman" color="black">Envoi de sms</font>
</TD></tr>

<TR><td colspan=3 align=left>
<font size=3 face="Times New Roman" color="navy">Num&eacute;ro destinataire :</font>&nbsp;&nbsp;&nbsp;
<input type=text size=10 maxlength=10 value="Destinataire" name="telephone" onFocus="this.value='';">
  ommettez le zero du d&eacute;but</TD></TR>

<TR><td colspan=3>&nbsp;</TD></TR>
<TR><TD align=left colspan=3 >
<font size=3 face="Times New Roman" color="navy">
votre m&eacute;ssage </font> :&nbsp;&nbsp;&nbsp;
<textarea cols=30 rows=4 id="textemessage" name="textemessage" onKeyPress='addelDigit()'>
</textarea></TD></TR>

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
<TD valign=top>Selectionner mot clef associe a votre campagne eventuelle<BR>
<?php
 echo $selectMC;
?>
</TD>
<TD valign=top>
<input type=button id='BoutonCampPerso' value="personnaliser campagne/selectionner destinataires" onClick="changeActionOuvrePerso(<?php echo "$codesession";?>)" disabled>
</TD>
<TD colspan=3 align=left> 
... et donc si vous voulez programmer son declenchement :<BR>
<input name=flagprogcamp type=checkbox onClick='afficheChampsDate()'>
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
<td  align=center><input type=button value="envoyer" onClick='VerifSubmit()'></TD></TR>
</form>
<td align=center>
<a href="http://sms.web-diffusion-france.com/ventes-sms/paiements.html"> <font size=4 face="Times New Roman" color="navy" weight="bold">Cliquez ici pour approvisionner votre compte en sms</font></A>
</TD></tr>

<TR><td colspan=3>&nbsp;</TD></TR>

<tr><td align=right colspan=3>
<a target=_blank href="http://web-diffusion-france.com"><font size=2 face="Times New Roman" color="navy" weight="bold">Services web-diffusion-france</font></A></TD></TR>
</table>

</body>
</html>