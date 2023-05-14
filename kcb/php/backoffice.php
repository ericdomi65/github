<?php
// BACKOFFICE DE LANCEMENT DE CAMPAGNES PROGRAMMEES


// initialisation des variables
$nom=0;
$prenom=0;
$pseudonyme=0;
$password=0;
$adresse1=0;
$adresse2=0;
$postal=0;
$ville=0;
$message="";
$dateenvoi="";
$rowsinclus="";
$Tabrowsinclus=null;
$fichier="";
$utilisateurs=null;
$resultUtil=null;
$credits=0; 
$nomUtil="";
$prenomUtil="";
$emailUtil="";
$mysql_link=null;
$voir=null;
$pseudo="";
$mdp="";
$campagne="";
$i=0;
$result=null;
$numrows=0;
$chaineDate='';



echo "<html><head><script language='Javascript' src='http://gratuitannonces-online.com/scripts/source-back.js'></script>";
echo "</head><body>";

$mysql_link=mysql_connect ("gratuitannonces-online.com", "ricdo_eric31", "xirtam") or die ('I cannot connect to the database because: ' . mysql_error());

$TabDate=getDate();
$chaineDate=$TabDate['year']."-".$TabDate['mon']."-".$TabDate['mday']." ".$TabDate['hours'].":".$TabDate['minutes'].":".$TabDate['seconds'];

// voir les campagnes associées au cpt

mysql_select_db("ricdolap11469net1401_ameliositeperso",$mysql_link); 
$requete = "SELECT * FROM campagne WHERE date_envoi >= '".$chaineDate."' and date_envoi <=date_add('".$chaineDate."',INTERVAL 1 day);";
$result = mysql_db_query("ricdolap11469net1401_ameliositeperso", $requete);
if($result != null){

	$numrows=mysql_num_rows($result);


if($numrows ==0)
{

echo "<script Language='Javascript'>";
echo "messageConsole(\"Pas de campagnes a envoyer aujourdhui\");";
echo "</script>";
echo(" Pas de campagnes a envoyer aujourdhui");
exit();
} // fin else if result!= null




for($i=0; $i < $numrows;$i++){ 
// parcours des enregs pour sortir les identifiants

$voir = mysql_fetch_array($result);
$mdp=$voir['mdp']; $pseudo=$voir['pseudo'];$campagne=$voir['nom_campagne'];
$rowsinclus=$voir['rows_inclus'];
$Tabrowsinclus=explode("-",$rowsinclus);
//identifier client par rapport a son pseudo et mdp;
$requete = "SELECT * FROM utilisateurs WHERE pseudo='".$pseudo."' AND mdp='".$mdp."';";
$resultUtil = mysql_db_query("ricdolap11469net1401_ameliositeperso", $requete); 

if(!$resultUtil)	{  // L 40

echo "<script Language='Javascript'>";
echo "messageConsole(\"warning : impossible identifier ".$pseudo.":Faites retour\");";
echo "messageConsole(\" puis recommmencer:". mysql_error()."\");";
echo "</script>";
continue;
	}
$utilisateurs = mysql_fetch_array($resultUtil);
$nomUtil=$utilisateurs['nom']; $prenomUtil=$utilisateurs['prenom'];
$credits=$utilisateurs['credits'];
$envoiemail=false;


if($credits == 0 || $Tabrowsinclus.length < $credits){$emailUtil=$utilisateurs['email'];$envoiemail=true;}
else{

// renseigner les autres paramettres campagne
$nom=$voir['nom'];
$prenom=$voir['prenom'];
$pseudonyme=$voir['pseudonyme'];
$password=$voir['password'];
$adresse1=$voir['adresse1'];
$adresse2=$voir['adresse2'];
$postal=$voir['postal'];
$ville=$voir['ville'];
$message=$voir['message'];
$dateenvoi=$voir['date_envoi'];
$fichier=$voir['fichier'];
$rowsinclus=$voir['rows_inclus'];


echo "<table border=1 width=100% align=center><tr><TD>";
echo "Nom Resp.:$nomUtil</TD><TD>Prenom Resp.:$prenomUtil</TD><TD>Credits restants:$credits</TD></TR>";
echo "<TR><TD>Message:<textarea cols=25 rows=4>$message</textarea></TD><TD>quand envoyer:$dateenvoi</TD>";
echo "<TD>N° Enregs &agrave; envoy:<input type=text size=50 value='$rowsinclus'></TD></TR></table>";
echo "<form target=_blank name=envoicampgprog method=\"post\" action=\"./envoiCampagneProg.php\">";
// insertion de tous les parametres campagne en caché

echo "<input type=hidden name=pseudo value=$pseudo>\n";
echo "<input type=hidden name=mdp value=$mdp>\n";
echo "<input type=hidden name=campagne value='$campagne'>\n";
echo "<input type=hidden name=message value=\"$message\">\n";
echo "<input type=hidden name=fichier value=$fichier>\n";
echo "<input type=hidden name=rows value=$rowsinclus>\n";
echo "<input type=hidden name=nom value=$nom>\n";
echo "<input type=hidden name=prenom value=$prenom>\n";
echo "<input type=hidden name=adresse1 value=$adresse1>\n";
echo "<input type=hidden name=adresse2 value=$adresse2>\n";
echo "<input type=hidden name=postal value=$postal>\n";
echo "<input type=hidden name=pseudonyme value=$pseudonyme>\n";
echo "<input type=hidden name=password value=$password>\n";
echo "<input type=hidden name=ville value=$ville>\n";
echo " <div id=".$i."><input type=button value=\"demarrrage Cpt a Rebour\" onClick=\"InitCpt(".$i.",'".$dateenvoi."','".$nomUtil."','".$prenomUtil."')\"></div>";

echo "<input type=submit value='envoyer'></form>";
}

if($envoiemail){
//envoyer un email au Resp. : $emailUtil
echo "<script Language='Javascript'>";
echo "messageConsole(\"envoi de courrier electronique au Responsable pour recharger compte\");";
echo "</script>";
}


}// fin for
}// fin if(result)
?>






