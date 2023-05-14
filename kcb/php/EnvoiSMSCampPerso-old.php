<?php 
/*
renseigner plus bas: L65
$Login="";
$Mdp="";
$Bdd="";
fichier d'envoi de campagnes personnalisées en fonction des destinataires et du parametrage du message(eventuellement) ou envoi différé(il existe un backoffice en ligne accessible uniquement au web mestre qui gere les envois retardés)
*/
// initialisation des variables
$m=0;
$resultatLM="";
$message='';
$messag='';
$messageparametre="";
$RangClient=0;
$MessageParam=true;
$credits=0; 
$mysql_link=null;
$voir=null;
$pseudo=null;
$mdp=null;
$idclient=0;
$expire=false;
$efface=false;
$erreuroperateur=false;
$select="";
$selectLM="";
$selectMC="";
$i=0;//L30
$c=0; 
$nbcamp=0;
$data=null;
$resultat=false;
$fichier='';
$num=0;
$rows=0;
$tel = 0;
$mobile=0;
$reponse="";
$Numexpire=0;
$Numdelivre=0;
$Numefface=0;
$Numerreuroperateur=0;
$Nummalformation=0;
$Numerreurinconnue=0;
$Numoperateurrejet=0;
$NumQueued=0;
$TabCampagne=Array();
$TabchampsRowsInclus=null;
$message=$_POST['textemessage'];
$messag="";
$codesession=$_POST['codesession'];
$campagne=$_POST['campagne'];  
$champsRowsInclus = $_POST['champsRowsInclus'];
$flagprogcamp =  isset($_POST['flagprogcamp'])?$_POST['flagprogcamp']:null;
$host=$_SERVER['REMOTE_ADDR'];
// verification de la connection par la session


//L60
function valOk($var)
{
return ($var != '' );
}
//L65
$mysql_link=mysqli_connect("localhost:3308", "kcb_data_struct", "XirTam_65300") or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));
mysqli_select_db($mysql_link,"kcb"); 
if (!$mysql_link) 
  die("Connection failed: " . mysqli_connect_error());     

$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysqli_error($mysql_link); return;}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); return;}

$idclient=$voir['idclient'];

if($idclient != 0)	{

//identifier client par rapport a son pseudo et mdp;

$requete = "SELECT * FROM utilisateurs WHERE idclient=$idclient;";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{  
echo "warning : impossible  de vous identifier:Faites retour puis recommmencer: ".mysqli_error($mysql_link);
	return; 
	}
$voir = mysqli_fetch_array($result);

$credits=$voir['credits'];
if($credits <= 10 )	{
	echo("votre credit de sms est presque &eacute;puis&eacute;.<BR>");
	echo(" Vous pouvez en racheter via PAYPAL");	
	echo "<a href='http://sms.web-diffusion-france.com/ventes-sms/paiements.html'>";
	echo "<font size=4 face='Times New Roman' color='navy' weight='bold'>";
	echo "Cliquez ici pour approvisionner votre compte en sms</font></A>";
		}
			} // fin if pseudo!=null


// traitement de la chaine RowsInclus 	Ligne 90

$TabchampsRowsInclus= explode("-",$champsRowsInclus);
//Ligne 110
//echo "Valeurs a traiter:\n";
$TabchampsRowsInclus=array_filter($TabchampsRowsInclus, "valOk");
//exit();

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




// voir les campagnes associées au cpt
$requete = "SELECT * FROM campagne WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);

if($result != null){

// campagnes actives
	
	$numrows=mysqli_num_rows($result);

//L138
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
$nbcamp++;
			}
else{
$resultatLM=true;
//$TabCampagne[$i]=array(0=>$voir['nom_campagne'],1=>null,2=>null);
//$selectLM .="<option value=\"".$TabCampagne[$i][0]."\">".$TabCampagne[$i][0]."\n";
$selectLM .="<option value=\"".$voir['nom_campagne']."\">".$voir['nom_campagne']."\n";

	} // fin else refliste ==0
$i++;
// echo " et i= $i<BR>";
				} // fin while
	$select .="</select>";
	$selectLM .="</select>";
	
}
// gestion de la campagne L169

$requete = "SELECT * FROM campagne WHERE idclient=$idclient and nom_campagne='$campagne'";
$result = mysqli_query($mysql_link,$requete);
if($result != null)	{
// campagnes actives
$i=0;
	$voir=mysqli_fetch_array($result);
	$fichier = $voir['fichier'];
	$mobile  = $voir['mobile'];
	$edition = $voir['nb-edition'];
if(empty($message)){
	$MessageParam=false;
	echo"<BR>Message téléchargé à partir de votre parametrage";
	$message = addslashes($voir['message']);
//	echo "Message = ".$message;
		}

//echo "debug = ".$TabCampagne; L187

// enregistrement des parametres campagne au cas ou elle serait programmée

if(isset($flagprogcamp))	
		    {
echo(" <BR>Vous avez cliqué sur la case relative à la programmation de la campagne, je vais donc planifier celle ci pour le...");
$annee=$_POST['annee'];
$mois=$_POST['mois'];
$jour=$_POST['jour'];
$heure=$_POST['heure'];
$minutes=$_POST['minutes'];
if(!isset($annee) || !isset($mois) || !isset($jour) || !isset($heure) || !isset($minutes) )echo ("Erreur : un ou plusieurs parametres manquants. Impossible d'enregistrer la campagne");
else 	{
$date_envoi=$annee."-".$mois."-".$jour." ".$heure.":".$minutes;

$requete = "UPDATE campagne SET message='".$message."', date_envoi='".$date_envoi."', rows_inclus='".$champsRowsInclus."'  WHERE idclient=$idclient and nom_campagne='$campagne'";
$result = mysqli_query($mysql_link,$requete);
if($result == null)	{
echo("Il faut recommencer ou nous contacter car : probleme avec la base de données :".mysqli_error($mysql_link));
			} // if result != null
else
{
 echo("...Campagne planifiée pour le $jour $mois $annee à $heure:$minutes<BR> à bientôt!");
}
	} // fin else if (!isset...)
	//sortir du programme
	exit();
		}// fin if(isset($flagprogcamp)
	} // fin if($result != null)	
else{ echo("Il faut recommencer ou nous contacter car : probleme avec la base de données :".mysqli_error($mysql_link));exit();}
// L220
//echo "Recuperation fichier de mobiles...";
$handle = fopen("../campagnesbtoc/".$fichier, "r");

if(($data = fgetcsv($handle, 500, ";")) != FALSE)
	{ 
//echo " ...OK";
	}else 	{
echo "...Impossible recuperer fichier de données";exit();
		}

// debut de la boucle d'envoi;


//echo "<BR>identification colonne mobile...";
for ($c=0,$j=1; $c < $num; $c++,$j++){
if($j==$mobile)	{
	if(!is_numeric($data[$c]))	{

echo " La colonne mobile du fichier uploadé ne colle pas avec le rang de la variable specifiée<BR>";
echo " dans le parametrage de la campagne";
exit();
				}
	else break;
		}
			} // fin for	
					 // Ligne 247
//echo("..colonne mobile OK. OUVERTURE console:");
echo "<html><head>";
echo "<script language='Javascript'>\n";
echo " var wdf=window.open(\"\",\"\",\"statusbar=0,toolbar=0,menubar=0,scrollbars=yes\");\n";
echo " var doc=wdf.document;\n";
$rows=0;
$RangClient=current($TabchampsRowsInclus);

// url d'accès à la passerelle
$url = "https://api.tm4b.com/v1/sms";

 $ch = curl_init(); //parametres
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);   L300
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //  retourne une variable  au lieu de l'afficher directement
curl_setopt($ch,CURLOPT_POST, 1); 

do{
$requete = '{"sandbox":true,"messages":['; 
$num = count($data);

if($rows != $RangClient){$rows++;continue;}
else {
$RangClient=next($TabchampsRowsInclus);
//decortication du message au cas ou il est parametré
$messageparametre=$message;
for($i=0; $i < $num;$i++)	
			{
$j=$i+1;

if($j==$mobile)	{
$tel=$data[$i]; // on renseigne le numero mobile de l'enregistrement courant
//echo("<BR>mobile = ".$tel);
		}else	{

if(!(strripos($messageparametre,"[".$j."]") ===false))	{
$messageparametre=str_replace("[".$j."]",$data[$i],$messageparametre);
//echo("<BR> j= $j, data[i]= $data[$i]");	
							}
			}

			} // fin for
//echo "<BR>message = $messageparametre<BR>"; L281
}


$taleau_json=array();
$param["source_address"]="wdf";
$param["destination_address"]="+33$tel"; 
$param["content"]="$message";

$taleau_json=json_encode($param);
$requete .=$taleau_json;
$requete .=']}';

echo(" doc.writeln(\"requete envoi SMS = $requete\");");
 // active la méthode POST 

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: keep-alive','Content-Length: '.strlen($requete),'Content-Type: application/json', 'Accept: application/json','Api-Key: 724b54d63907b97c9545e10439bc50f6'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $requete); // requete

// execute la connexion CURL

$reponse = curl_exec($ch);
// demande du statut de delivrance

$erreur=false;
$delivery_state="";
$requetteBdd="";

if (strlen($reponse) > 0) 	{
//echo("<BR>reponse = $reponse<BR>");
    $requetteBdd=json_decode($reponse, true); 

//L321
    if (!(json_last_error() == JSON_ERROR_NONE and is_array($requetteBdd)))
        {$requetteBdd='Données JSON invalides';$erreur=true;}
				} 
else {$requetteBdd='Aucune données JSON'; $erreur=true;}


        

if(!$erreur && is_array($requetteBdd))	{

$delivery_state=$requetteBdd["messages"][0]["delivery_state"];

echo " doc.writeln(\"<BR>$tel : $delivery_state<BR>\");\n";
		}
$reponse=$delivery_state;
//DELIVERED,EXPIRED,FAILED&REJECTED,ACCEPTED&QUEUED,UNKNOWN	
if($reponse=='DELIVERED'){$Numdelivre +=1; $messag="SMS recu";}
else if($reponse=='EXPIRED'){$Numexpire +=1; $messag="Erreur:Délais expiré"; }
else if($reponse=='FAILED'){ $Numefface +=1; $messag="Erreur:Message non délivré pour une ou plusieurs raisons (ex:destination invalide";}
else if($reponse=='REJECTED'){ $Numefface +=1; $messag="Erreur : message ou numéro invalide";}
else if($reponse=='ACCEPTED' || $reponse=='SENT'){$NumQueued +=1;$messag="message en file d'attente au niveau opérateur mobile";}
else if($reponse=='QUEUED'){$NumQueued +=1;$messag="Message mis a la queue : veuillez attendre delivrance";}
else if($reponse=='UNKNOWN'){$Numerreurinconnue +=1; $messag="Erreur inconnue";}

$rows++;
echo("doc.writeln(\".$messag.\");");
	} while(($data = fgetcsv($handle, 500, ";")) != FALSE);

if(!$erreur){
$i=count($TabchampsRowsInclus);
echo " doc.writeln(\"fin envoi du message aux correspondants\");\n";
//echo "fin envoi du message aux correspondants";

echo " doc.writeln(\"enregistrement des resultats...\");\n";
$requete = "INSERT INTO histocamp (idclient,nom_campagne,date_campagne,nbmobiles,EXPIRED,FAILED,UNKNOWN,QUEUED) VALUES";
$requete .= "($idclient,'$campagne',curdate(),$i,$Numexpire,$Numefface,$Numerreurinconnue,$NumQueued);";


$result = mysqli_query($mysql_link,$requete); 
if(!$result){	
//echo "Erreur enregistrement stats campagne:".mysqli_error($mysql_link);
echo " doc.writeln(\"Erreur enregistrement stats campagne:".mysqli_error($mysql_link)."\");\n";
	    }
//echo "...OK<BR>"; L363
//identification du statut message puis decrementation credit eventuelle.

if($Numdelivre >0 || $NumQueued > 0)
	{
$num=$Numdelivre + $NumQueued;
$message=$num." messages délivrés ou en file d'attente(sur le point de l'etre)";
echo " doc.writeln(\"<BR>$message<BR>\");\n";
$credits -= ($Numdelivre + $NumQueued);

$requete = "update utilisateurs  set credits=$credits WHERE idclient=$idclient;";
$result = mysqli_query($mysql_link,$requete);

if(!$result)
		{
//echo "Erreur requete select sur utilisateurs:".mysqli_error($mysql_link);

echo " doc.writeln(\"Erreur requete select sur utilisateurs:".mysqli_error($mysql_link)."\");\n";

		}
mysqli_close($mysql_link);
	}


if( $Numerreurinconnue >0)	{
	$message .= " \nUne erreur ind&eacute;pendante de votre volont&eacute; est intervenue. Ainsi votre credit de SMS reste le meme";
	$message.= " Vous pouvez tenter de modifier le message ou de le renvoyer tel quel";
	$message.=" Erreur technique niveau operateur t&eacute;lecom ($Numerreurinconnue messages)";
				}

	if($Numefface > 0)
				{
$message = " Erreur:Des messages non délivrés pour une ou plusieurs raisons (ex:destination invalide):\n message ou numéro invalide($Numefface messages)";

				}
echo " doc.writeln(\"Retour : $message\");\n";
} //fin if !$erreur
else echo("doc.writeln(\"Erreur : $requetteBdd\");");
echo "</script>";
//L396
?>

<script Language="Javascript" src="../jscripts/source-sms.js"></script>
<script Language="Javascript">
var TabCampagne=new Array();

<?php
if($resultat)
 for($x=0; $x < $nbcamp ;$x++){
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

</html>