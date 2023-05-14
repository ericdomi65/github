<?php

/*
*	creation d'une campagne a partir d'un fichier csv ou d'une liste de numero mobiles aleatoire qu'on y rattache
*	oubien a partir d'une campagne existante
*	Fait appel suivant le cas à envoi_cpg_liste.php(Liste de mobiles) ou envoi-campagne.php		
*/
$i=0;
$j=0;
$x=0;
$resultat=false;
$resultat_listes_mob=false;
$select="";
$codesession=$_REQUEST['codesession'];
$idclient=0;
$ref_liste=0;
$libelle="";
$selectLM="";


/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"

/*****************************************************************/
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

$requete = "SELECT * FROM session WHERE codesession=".$codesession.";";

$result = mysqli_query($mysql_link,$requete); 
if(!$result){echo "Erreur 1 impossible identifier la session:".mysqli_error($mysql_link); return;}


$voir = mysqli_fetch_array($result);
if(! $voir){echo "Erreur 2 impossible identifier la session:".mysqli_error($mysql_link); return;}

$idclient=$voir['idclient'];

$requete = "SELECT * FROM identite WHERE identite=$idclient";
$result = mysqli_query($mysql_link,$requete); 

if(!$result){echo "Erreur 2 impossible interroger identite:".mysql_error($mysql_link); exit();}

$voir = mysqli_fetch_array($result);



$requete = "SELECT * FROM campagne WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete);
if($result != null){
// campagnes actives
	$resultat=true;
	$numrows=mysqli_num_rows($result);
	$select = "<select style='visibility:hidden;' name=\"old_campagne\" onchange=\"this.selectedIndex > 0?afficheDetailsCmpgn(this.selectedIndex):void(0)\">";
	$select .="<option value=\"aucune\">aucune campagne \n";
$TabCampagne = Array();
	while ($i < $numrows)	{
$voir = mysqli_fetch_array($result);
$TabCampagne[$i][0] = $voir['nom_campagne'];
$TabCampagne[$i][1] = $voir['fichier'];
$TabCampagne[$i][2] = $voir['mobile'];
$TabCampagne[$i][3] = $voir['ref_liste'];

//39
		$select .="<option value=\"".$TabCampagne[$i][0]."\">".$TabCampagne[$i][0]."\n";
$i++;
// echo " et i= $i<BR>";
				} // fin while
	$select .="</select>";

	
		} // if result != null
else 
die ('I cannot connect to the database because: ' . mysql_error($mysql_link));

//telechargement listes mobiles attachées au cpt:

$requete = "SELECT ref_liste,libelle FROM mobile_random WHERE idclient=$idclient GROUP BY ref_liste";
$result = mysqli_query($mysql_link,$requete);

	$numrows=mysqli_num_rows($result);
	if($numrows > 0)$resultat_listes_mob=true;
	$selectLM= "<select  name=\"liste_mob\" onchange=\"this.selectedIndex>0?document.forms[0].action='./envoi_cpg_liste.php':document.forms[0].action='./upload-campagne.php'\">";
	$selectLM .="<option value=\"0\">aucune liste</option>";
	while ($x < $numrows)	{
	
	$voir = mysqli_fetch_array($result);
	$ref_liste=$voir['ref_liste'];
	$libelle=$voir['libelle'];
	$selectLM .="<option value='$ref_liste'>$libelle</option>";
$x++;

				} // fin while
	$selectLM .="</select>";

mysqli_close($mysql_link);
?>
<html>
<head>
<script Language="Javascript" src="../jscripts/source-sms.js">
</script>
<script Language="Javascript">
TabCampagne=new Array();

<?php
if($resultat)
 for($x=0; $x < $i ;$x++){
echo "TabCampagne[".$x."]=new Array();\n";
for($m=0; $m < 4 ; $m++){
echo "TabCampagne[".$x."][".$m."]='".$TabCampagne[$x][$m]."';";
echo "\n";
}
}
?>
</script>
<script Language=Javascript src="../jscripts/menu.js"></script>
</head><body bgcolor='lightyellow'>
<script Language="Javascript">Menu(<?php echo($codesession); ?>);</script>
<table align=center width=100% border=1>
<tr>
<td align=center valign=top>
<form target=_blank method="post" enctype="multipart/form-data" action="./envoi-campagne.php">
 <input type="hidden" name="MAX_FILE_SIZE" value="30000" />

  Pour faire campagne , envoyez ce fichier: <input name="userfile" type="file" /><BR>
<input type=hidden name=idclient value=<?php echo $idclient; ?>>
<input type=hidden name=codesession value=<?php echo $codesession; ?>>
</TD>
<TD valign=top>...Ou bien : Choisissez eventuellement une liste aleatoire de mobiles afin de l'attacher a la campagne
<?php if($resultat_listes_mob)echo("$selectLM");?>
</TD>
<TD valign=top>
<U>Deuxiemement:</U><BR>
<?php if($resultat) echo $select ?><BR>
<input type=radio name=reload value=true onClick='document.all.old_campagne.style.visibility="visible"'> Remplacer Listing<BR>
<input type=radio name=reload value=false 
onClick='document.all.old_campagne.style.visibility="hidden"; document.all.campagne.style.visibility="visible"'> Nouvelle Campagne<BR>
<input type=text name=campagne size=40 style='visibility:hidden'>
</TD></TR>
<TR><TD valign=top colspan=2>
<U>Troisiemement : </U>(en cas de telechargement de fichier csv), renseignez ces champs :<BR>
<input type=text name=colmob size=2 maxlength=2> nieme colonne (a partir de 1) du mobile dans fichier<BR>
<input type=text name=nom size=2 maxlength=2> nieme colonne (a partir de 1) du nom dans fichier<BR>
<input type=text name=prenom size=2 maxlength=2> nieme colonne (a partir de 1) du prenom dans fichier<BR>
<input type=text name=pseudonyme size=2 maxlength=2> nieme colonne (a partir de 1) du pseudonyme dans fichier<BR>
<input type=text name=password size=2 maxlength=2> nieme colonne (a partir de 1) du mot de passe dans fichier<BR>
<input type=text name=adresse1 size=2 maxlength=2> nieme colonne (a partir de 1) de l' adresse1 dans fichier<BR>
<input type=text name=adresse2 size=2 maxlength=2> nieme colonne (a partir de 1) de l' adresse2 dans fichier<BR>
<input type=text name=ville size=2 maxlength=2> nieme colonne (a partir de 1) de la ville dans fichier<BR>
<input type=text name=email size=2 maxlength=2> nieme colonne (a partir de 1) de l' email dans fichier

</TD>
<td align=center><textarea cols=35 rows=5 name=message></textarea><br>Entrez un message par defaut ou laissez vide (vous pourrez entrer un message parametré plus tard )
</TD>
</TR>
<TR>
<TD align=left width=20 height=20 valign=top>
<div style="color:'red';border:solid;border-color:'red';border-width:1px" width=12 height=12  id="info1" ONMOUSEOVER='afficheInfo("tip1");'><h3><B>?</B></H3></div>
</TD><TD align=right colspan=2>
<div align=center style="visibility:hidden;border:solid;border-width:1;border-color:blue;witdh:50px;height:100px;color:blue;" id="tip1" ONCLICK='this.style.visibility="hidden";'>
Vous pouvez envoyer une campagne en parametrant le message depuis l'interface appropriée en cliquant sur le bouton "parametrer campagne" qui apparait sur la page d'accueil (dès que vous êtes logué),dès que vous avez selectionné une campagne dans la liste appropriée
</div></TD>
</TR><TR>
<td align=center valign=top colspan=3>
  <input type="submit" value="Envoyer le fichier" />
</form>
</TD></TR>
</table>
</body></html>