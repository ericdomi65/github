<?PHP
$codesession=$_REQUEST['codesession'];
$idclient=0;
$i=0;
$numMobiles=0;


/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/


// connection a la BDD cm188797 | telechargement tableau des mobiles
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/

$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "gestion-listes-mobiles.Erreur 1 impossible identifier la session:".mysqli_error($mysql_link); exit();}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "gestion-listes-mobiles.Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); exit();}

$idclient=$voir['idclient'];

	$requete="SELECT ref_liste,libelle FROM mobile_random WHERE idclient=$idclient GROUP BY ref_liste;";
	$result=mysqli_query($mysql_link,$requete); 
	if($result==null){ echo("Err 1 Impossible telecharger liste des mobiles".mysqli_error($mysql_link));exit();}
	$numrows=mysqli_num_rows($result);
	//creation table;
	$html="<table width=50% border=1 cellspacing=5 align=center><TR><TH>reference liste</TH><TH>Libelle liste</TH><TH>nombre de mobiles</TH><TH>invalides</TH></TR>";

	while($i < $numrows){
	$voir = mysqli_fetch_array($result);
	$ref_liste=$voir['ref_liste'];	
	$libelle=$voir['libelle'];	
	

// requete a faire en 2 fois vu que SQL est null a chier
	// requete 1:
	$requete="SELECT count(invalide) invalides FROM mobile_random WHERE idclient=$idclient AND ref_liste=$ref_liste and invalide=1";
	
	$result2=mysqli_query($mysql_link,$requete); 
	if($result2==null){ echo("Err 2 Impossible telecharger liste des mobiles".mysqli_error($mysql_link));exit();}
	$voir = mysqli_fetch_array($result2);
	$invalides=$voir['invalides'];
	
		// requete 2:
	$requete="SELECT count(mobile)mobiles FROM mobile_random WHERE idclient=$idclient AND ref_liste=$ref_liste";
	
	$result2=mysqli_query($mysql_link,$requete); 
	if($result2==null){ echo(" Impossible telecharger liste des mobiles".mysqli_error($mysql_link));exit();}
	$voir = mysqli_fetch_array($result2);
	$numMobiles=$voir['mobiles'];	
	$html.="<TR><TD>$ref_liste</TD><TD>$libelle</TD><TD>$numMobiles</TD><TD>$invalides</TD><TD><A HREF='javascript:void(0)' onClick='effaceListe($ref_liste)'><font color='red'>Effacer liste</font></A></TD><TD><A HREF='javascript:void(0)' onClick='effaceInvalides($ref_liste)'><font color='red'>Effacer invalides</font></A></TD>";

	$html.="<TD><A HREF='javascript:void(0)' onClick=\"allongerListe($idclient,$ref_liste,'$libelle')\"><font color='red'>agrandir liste de 200 mobiles</font></A></TD></TR>";	
	$i++;
			} // fin while
	// fin table
	$html .="</table>";
mysqli_close($mysql_link);
?>

<html><head>
<script Language="Javascript" src="../jscripts/listes-mobiles.js"></script>
<script Language="Javascript" src="../jscripts/menu.js"></script>
</head>
<body>
<script Language="Javascript">
Menu(<?php echo("$codesession");?>);</script>
<P align=center>
Cette interface vous permet de creer une liste aleatoire (ou plusieurs) de mobiles afin  de l'attacher a une campagne(dans le menu creation campagnes).</P>
<P  align=center>Vous pouvez d'autre part visionner les données esssentielles relatives a chaque liste existante (attachee a votre compte), afin de les allonger, les raccourcir, les supprimer
ou supprimer les mobiles invalides(qui ont donnes une reponse qui implique qu'il n'y a pas de ligne attachée)</P>

<P  align=center><?php echo("$html");?></P>
<form action="./gestion-listes-mobiles.php" method="post">
<input type=hidden name=codesession value=<?php echo("$codesession");?>>
<input type=submit value="Actualiser table">
</form>

<P  align=center>
<div style="border:solid;border-color:'red'">Creer liste aleatoire de mobiles<BR>
<input type=hidden id=idclient value=<?php echo("$idclient");?>>
<input type=text size=4 maxlength=3 id=nbmobiles value=100>Nombre de mobiles a creer<BR>
<input type=text size=20 maxlength=20 id=libelle>Libelle de la liste (pour memo)<BR>
<input type=button value="Envoyez" onClick='creerListe()'></div>
</P>
</body></html>
















