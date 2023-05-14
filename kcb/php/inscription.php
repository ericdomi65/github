<?php
// INSCRIPTION D'APRES LE PASSWORD ET LE PSEUDO SACHANT QUE JE RETROUVE LE CLIENT D'APRES SON EMAIL
// generation d'un IDC DE CLIENT QUI PERSISTE DANS TOUTES LES TABLES EN ETANT INDEXE

/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/



$voir=null;
$pseudo=null;
$mdp=null;
$email=null;
$civil=null;
$organe=null;
$nom=null;
$prenom=null;
$adresse1=null;
$adresse2=null;
$codepost=0;
$ville=null;
$tel=0;
$site=null;
$theme=null;
$champspseudo=true;
$champsmdp=true;
$champsemail=true;
$paiements=false; //22
if(empty($_POST['pseudo']))$champspseudo=false;
if(empty($_POST['passe']))$champsmdp=false;
if(empty($_POST['email']))$champsemail=false;

 if(!$champspseudo) { echo "Vous devez remplir le champs Pseudonyme S.V.P";return;}
 if(!$champsmdp )   {echo "Vous devez remplir le champs mot de passe S.V.P";return;}
 if(!$champsemail)   {echo "Vous devez remplir le champs adresse email S.V.P";return;}

$pseudo=$_REQUEST['pseudo'];
$mdp=$_REQUEST['passe'];
$email=$_REQUEST['email'];

//35
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));


// verification unicité mdp;
	$requete = "SELECT * FROM identite WHERE mdp=".$mdp; //40
	$result = mysqli_query( $mysql_link,$requete);

	$numrows=0;

	if($result != null)
	$numrows=mysqli_num_rows($result);

	if($numrows != 0 )	{

		echo "votre mot de passe est déja pris. Veuillez bien choisir un mot de passe unique,<BR>";
		echo " <BR> Faite retour arriere pour recommencer";
echo("<script Language=\"Javascript\">");
echo("alert(\"votre mot de passe est déja pris. Veuillez bien choisir un mot de passe unique, afin que personne ne puisse acceder a votre compte,faites Ok pour recommencer\");");
echo("history.go(-1);</script>");
		exit();		
			}

// fin verif unicite mdp Ligne : 58
$nom=$_POST['nom'];
$prenom=$_POST['prenom'];
//verif unicite email
	mysql_select_db("cm188797",$mysql_link); 
	$requete = "SELECT * FROM identite WHERE email='$email'";
	$result = mysqli_query( $mysql_link,$requete);

	$numrows=0;

	if($result != null)
	$numrows=mysqli_num_rows($result);

	if($numrows == 1 ){


		echo "votre email est déja pris. Votre mot de passe va vous etre envoyé,<BR>";
// 75
echo("<script Language=\"Javascript\">");
echo("alert(\"votre email est déja pris. Votre mot de passe va vous etre envoyé,faites Ok pour poursuivre\");");
echo("</script>");
// envoie email
$message="Madame,Monsieur $nom, $prenom : nous utilisons votre patronyme pour vous indiquer que le mail est authentique.<BR>";
$message .="Vous avez requis le mot de passe de votre compte chez web-diffusion-france.<BR>";
$message .="Mot de passe : $mdp<BR>Connection au compte: <A Href='http://gratuitannonces-online.com/php/compte.php?mdp=$mdp&email=$email'>cliquer ici</A><BR>";
$message .="Connection plateforme envoi campagne : <A Href='http://sms.web-diffusion-france.com/ventes-sms/connectionsms.html'>Cliquer ici</A>";
$message .="<BR> gratuitannonces-online.com est un site qui appartient à web diffusion France. contactez nous pour plus d'info : 09.50.111.333";
$to  = "$nom $prenom<$email>"; 
/* sujet */
$subject = "votre mot de passe chez web-diffusion-france";
/* message */
/* Pour envoyer du mail au format HTML, vous pouvez configurer le type Content-type. */
/*$headers  = "MIME-Version: 1.0\r\n";*/
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
/* D'autres en-têtes : errors, From cc's, bcc's, etc */
$headers .= "From: Web Diffusion France<services@web-diffusion-france.com>\r\n";
/* et hop, à la poste */
mail($to, $subject, $message, $headers);
		exit();	
			}
// 98

$civil=$_POST['civil']; $organe=$_POST['organe']; $adresse1=$_POST['adresse1']; $adresse2=$_POST['adresse2']; $codepost=$_POST['codepost']; $ville=$_POST['ville'];
$tel=$_POST['tel']; $site=$_POST['site']; $theme=$_POST['theme'];
// enregistrer nouvo client.
echo("<script Language=\"Javascript\">");
if(empty($civil)){	echo("alert(\"Etat civil non rempli,faites Ok pour recommencer\");");echo("history.go(-1);</script>");};
if(empty($organe)){	echo("alert(\"Organisation  non renseignee,faites Ok pour recommencer\");");echo("history.go(-1);</script>");};
if(empty($adresse1)){	echo("alert(\"adresse non renseignee,faites Ok pour recommencer\");");echo("history.go(-1);</script>");};
if(empty($adresse2))$adresse2=" ";
if(empty($codepost)){echo("alert(\"code postal non renseigne, faites Ok pour recommencer\");");echo("history.go(-1);</script>");};
if(empty($ville)){	echo("alert(\"ville non renseignee, faites Ok pour recommencer\");");echo("history.go(-1);</script>");};
if(empty($tel)){	echo("alert(\"telephone non renseigne, faites Ok pour recommencer\");");echo("history.go(-1);</script>");};
if(empty($site))$site="Pas de site ";
if(empty($theme))$theme="pas de theme";

//CREATION INDENTITE/ L 114
$identite=mt_rand(1000000,100000000);
$aujourd=getDate();
$chaineDate=$aujourd["year"]."-".$aujourd["mon"]."-".$aujourd["mday"];
$requete = "insert into IDENTITE (identite,  inscription,civilite, nom , prenom ,organisation ,adresse1,adresse2,ville,codepostal,telephone,passe,email,pseudo,siteinternet,theme)";
$requete .= " values($identite,'$chaineDate','$civil','$nom','$prenom','$organe','$adresse1','$adresse2','$ville',$codepost,$tel,'$mdp','$email','$pseudo','$site','$theme');";
	$result = mysqli_query( $mysql_link,$requete);
if(!$result){

echo(" Un probleme technique nous empeche d'enregistrer votre inscription, nous en somme simultanement avertis et vous tiendrons au courant des que possible<BR");
echo(" au 09.50.111.333 H.B");
$message ="probleme enregistrement client. Pbml BDD nom= $prenom $nom";
$to  = "$nom $prenom<$email>"; 
/* sujet */
$subject = "WDF Marketing prblm enregt client. Pbml BDD nom= $prenom $nom";
/* message */
/* Pour envoyer du mail au format HTML, vous pouvez configurer le type Content-type. */
/*$headers  = "MIME-Version: 1.0\r\n";*/
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
/* D'autres en-têtes : errors, From cc's, bcc's, etc */
$headers .= "From: Web Diffusion France<services@web-diffusion-france.com>\r\n";
/* et hop, à la poste */
mail($to, $subject, $message, $headers);
echo("<script Language=\"Javascript\">");
echo("alert(\"faites Ok pour retenter\");");echo("history.go(-1);</script>");
		exit();	
}


// creer la session
$codesession=mt_rand(1000000,100000000);
$chaineHeure=$aujourd["hours"].":".$aujourd["minutes"].":".$aujourd["seconds"];


$requete = "INSERT INTO session (idclient,codesession,datedebut,heuredebut)";
$requete.=" values($identite,".$codesession.",'".$chaineDate."','".$chaineHeure."');";

	$result = mysqli_query( $mysql_link,$requete);
if(!$result)die ("Erreur enregistrement session:".mysql_error());
// creation session.
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
<script Language="Javascript" src="http://gratuitannonces-online.com/scripts/source-sms.js">
</script>
</head><body bgcolor='lightyellow'>
<? if (file_exists('../ventes-sms/paiements.html') )
	{
	include('../ventes-sms/paiements.html');
		//exit();
	}
else
// inscription terminée, loger le client pour access a l'interface.
	echo(" INSCRIPTION TERMINEE, vous etes logu&eacute;. Vous pouvez parametrer votre campagne <B><U>avant d'acheter des cr&eacute;dits.</U></B>");
?>
<p align=center>
<input type=text name='credits' size=4 value=50 disabled> credits restants
<form name=envoisms method=post action="http://gratuitannonces-online.com/php/envoisms.php">
<input type=hidden name=saison value=<? echo "$codesession";?> >
<table width=500 align=center border=0 cellpading=5 cols=3  bgcolor="lightyellow">

<tr><td width=100% bgcolor="cyan">
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
<textarea cols=30 rows=4 name="textemessage"  onKeyPress='addelDigit()'></textarea></TD></TR>

<tr><td colspan=3 align=center>
<input type=text name="compteur" value=160  size=2 disabled>
<font weight="bold" size=3 face="Times New Roman" color="navy">Caractères restants</font>
</td></tr>

<tr><td align=center><input type=button value="envoyer" onClick='VerifSubmit()' class=bouton></TD></TR>

<tr><td align=center>
<a href="http://sms.web-diffusion-france.com/ventes-sms/paiements.html"> <font size=4 face="Times New Roman" color="navy" weight="bold">Cliquez ici pour approvisionner votre compte en sms</font></A>
</TD></tr>

<TR><td colspan=3>&nbsp;</TD></TR>

<tr><td align=right colspan=3>
<a target=_blank href="http://web-diffusion-france.com"><font size=2 face="Times New Roman" color="navy" weight="bold">Services web-diffusion-france</font></A></TD></TR>
</table>
</form>
</body>
</html>