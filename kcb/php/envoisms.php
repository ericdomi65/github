<?php 
/*
renseigner plus bas: L65
$Url="";
$Mdp="";
$Bdd="kcb_data_struct";
fichier INTERFACE ET TRAITEMENT d'envoi de campagnes(choisie par liste déroulante) ou à l'unité
*/
$message='';
$messag='';
$tel=0;
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
$i=0;
$c=0;
$data=null;
$resultat=false;  
$fichier='';
$num=0;   //L20
$mobile=0;
$Numdelivre=0;  //  if( $expire || $efface || $erreuroperateur || $malformation || $erreurinconnue || $operateurrejet
$Numefface=0;
$Numerreuroperateur=0;
$Nummalformation=0;
$Numerreurinconnue=0;
$Numoperateurrejet=0;
$NumQueued=0;
$erreurinconnue=false;
$operateurrejet=false;
$TabCampagne=array();
$message=$_REQUEST['textemessage'];
$codesession=$_REQUEST['codesession'];
if(isset($_REQUEST['campagne']))
$campagne=$_REQUEST['campagne'];
$host=$_SERVER['REMOTE_ADDR'];
// verification de la connection par la session
// L 38
$Url="";
$Mdp="";
$Bdd="";

$mysql_link=mysqli_connect($Url,$Mdp,$Bdd) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));
if (!$mysql_link) 
  die("Connection failed: " . mysqli_connect_error());   
$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysqli_error($mysql_link); return;}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); return;}

$idclient=$voir['idclient']; 	// L51

if($idclient != 0)	{
//identifier client par rapport a son pseudo et mdp;
$requete = "SELECT * FROM utilisateurs WHERE idclient=$idclient;";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{ 
echo "warning : impossible  de vous identifier:Faites retour";
echo " puis recommmencer: ".mysqli_error($mysql_link);
	return; }
$voir = mysqli_fetch_array($result);

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
	return;
				}

			}

if (!empty($message) && isset($_REQUEST['telephone'])) 
{
$tel=$_REQUEST['telephone'];
if(strlen($tel)< 10)	{  echo "erreur de numérotation. votre mobile de destination n'a que".strlen($tel)."chiffres ...au lieu de 10.";
		    exit();
		}
			//L84
		else $tel=substr($tel,1);
}

else
{ echo "erreur de traitement réessayez ultérieurement...";
  exit();
}

$requete = '{"sandbox":true,"messages":['; // L93

$taleau_json=array();
$param["source_address"]="wdf";
$param["destination_address"]="+33$tel";  // type de route (pour la france, GD02 uniquement)
$param["content"]="$message";

$taleau_json=json_encode($param);
$requete .=$taleau_json;
$requete .=']}';

echo("requete envoi SMS = $requete");
 // url d'accès à la passerelle
//$url = "http://www.tm4b.com/client/api/http.php"; // initialisation curl
$url = "https://api.tm4b.com/v1/sms"; //initialisation curl
 $ch = curl_init(); //parametres
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // L 112 retourne une variable  au lieu de l'afficher directement(0)
curl_setopt($ch,CURLOPT_POST, 1); // active la méthode POST 

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: keep-alive','Content-Length: '.strlen($requete),'Content-Type: application/json', 'Accept: application/json','Api-Key: 724b54d63907b97c9545e10439bc50f6','Host '.$host));
curl_setopt($ch, CURLOPT_POSTFIELDS, $requete); // requete

// execute la connexion CURL

$reponse = curl_exec($ch);
curl_close($ch);

echo("<BR>travail sur la reponse...:<BR>");


$erreur=false;
$delivery_state="";
$destination_address="";
$id=""; //L130
$content="";
$requetteBdd="";

if (strlen($reponse) > 0) 	{
//echo("<BR>reponse = $reponse<BR>");
    $requetteBdd=json_decode($reponse, true); 

//L138
    if (!(json_last_error() == JSON_ERROR_NONE and is_array($requetteBdd)))
        {$requetteBdd='Données JSON invalides';$erreur=true;}
				} else
    {$requetteBdd='Aucune données JSON'; $erreur=true;}


        

if(!$erreur && is_array($requetteBdd))	{

$delivery_state=$requetteBdd["messages"][0]["delivery_state"];
//$destination_address=$requetteBdd["messages"][0]["destination_address"];
//$id= $requetteBdd["messages"][0]["id"];
//$content=$requetteBdd["messages"][0]["content"];
//$requetteBdd="statut msg: ".$delivery_state." telephone destination:".$destination_address." id requete: ".$id." message= ".$content."<BR>";
//echo("<BR>$requetteBdd<BR>");
		}

//L147 
		//DELIVERED,EXPIRED,FAILED&REJECTED,ACCEPTED&QUEUED,UNKNOWN	
$reponse=$delivery_state;
if($reponse=='DELIVRED'){$delivre=true; $messag="SMS recu";}
else if($reponse=='EXPIRED'){$expire=true; $messag="Erreur:Délais expiré"; }
else if($reponse=='UNKNOWN'){$erreurinconnue=true; $messag="Erreur inconnue";}
else if($reponse=='FAILED' || $reponse== 'REJECTED'){$operateurrejet=true; $messag="Erreur:message rejeté par l\'operateur";}
else if($reponse=='QUEUED' || $reponse=='ACCEPTED')$messag="Message mis a la queue : veuillez attendre delivrance";
// mise en BDD du statut et du message pour analyse ulerieure. L154

$requete = "INSERT INTO statuts(tel,statut,reponse,message) values($tel,'".$reponse."','".$messag."','".$message."');";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)echo "Erreur enregistrement sms:".mysqli_error($mysql_link);

//identification du statut message puis decrementation credit eventuelle.

if( !$expire  && !$erreurinconnue && !$operateurrejet)
	{

$credits=$credits-1;

$requete = "update utilisateurs  set credits=$credits WHERE idclient=$idclient;";
$result = mysqli_query($mysql_link,$requete);		
//L169
if(!$result)echo "Erreur requete select sur ident:".mysqli_error($mysql_link);

mysqli_close($mysql_link);
	}
else {

	echo " Une erreur ind&eacute;pendante de votre volont&eacute; est intervenue. Ainsi votre credit de SMS reste le meme<BR>";
	echo " Vous pouvez tenter de modifier le message ou de le renvoyer tel quel.<BR>";
	echo " Erreur technique niveau operateur t&eacute;lecom";
     }   	//L179

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
<script Language=Javascript src="../jscripts/menu.js"></script>
</head><body bgcolor='lightyellow'>
<script Language="Javascript">Menu(<?php echo($codesession); ?>);</script>
<P>
<?php echo $messag ?></P>

<p align=center>
<input type=text size=4 value=<?php echo $credits?> disabled> credits restants

<form name=envoisms method="post"  action="./envoisms.php?">
<input type=hidden name=codesession value=<?php echo "$codesession";?> >

<table width=500 align=center border=0 cellpading=5 cols=3  bgcolor="lightyellow">

<tr><td width=100% bgcolor="cyan" colspan=3>
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
<textarea cols=30 rows=4 id="textemessage" name="textemessage" onKeyPress='addelDigit()'>
<?
//php if( $expire || $efface || $erreuroperateur || $malformation || $erreurinconnue || $operateurrejet)echo "$messag";
?>
</textarea></TD></TR>

<tr><td colspan=3 align=center>
<input type=text name="compteur" value=160  size=2 disabled>
<font weight="bold" size=3 face="Times New Roman" color="navy">Caractères restants</font>
</td></tr>
<TR>
<td  align=center valign=top><input type=button value="envoyer" onClick='VerifSubmit()'></TD></TR>
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