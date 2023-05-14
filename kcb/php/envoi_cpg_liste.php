<?php

/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/



$reload=$_POST['reload'];
$idclient=$_POST['idclient'];
$liste_mob=$_POST['liste_mob'];

if($reload == 'true')
$campagne = $_POST['old_campagne'];
else $campagne = $_POST['campagne'];

$message=$_POST['message'];

$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));
mysqli_select_db($mysql_link,"kcb"); 

//identifier client par rapport a son pseudo et mdp;
$requete = "SELECT * FROM utilisateurs WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{  
echo "warning : impossible  de vous identifier:Faites retour";
echo " puis recommmencer: ".mysqli_error($mysql_link);
	exit(); }
$voir = mysqli_fetch_array($result);

$credits= $voir['credits'];
$litige = $voir['litige'];

echo " Verification du statut de votre compte...";

if($litige =='true') { echo " N'oubliez pas de regler votre litige avec nous avant de demarrer une autre campagne";
	      echo " <BR> A bientot. Service clientelle : 09.50.55.33.33"; exit();
	    }	
echo "...OK.<BR>";







echo "Enregistrement des variables de la campagne...:<BR>";
$TabDate=getDate();
$chaineDate=$TabDate['year']."-".$TabDate['mon']."-".$TabDate['mday']." ".$TabDate['hours'].":".$TabDate['minutes'].":".$TabDate['seconds'];

if($reload=='false')
		{
$requete ="INSERT INTO campagne (idclient,nom_campagne,date_creation,ref_liste,message) VALUES (";
$requete .="$idclient,'$campagne','$chaineDate',$liste_mob,'$message')";



		} //fin if(!reload)

// MISE A JOUR paramettres colonne
if ($reload=='true')	{
// update possible des parametres colonne
$requete="UPDATE campagne set date_creation='$chaineDate',ref_liste=$liste_mob";
$requete .=" WHERE idclient=$idclient and nom_campagne='$campagne';";
		} // if reload =true
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{  
echo "warning : impossible  Enregistrer parametres : Faites retour";
echo " puis recommmencer: ".mysqli_error($mysql_link);
	exit(); }
echo "..............................OK<BR>";
mysqli_close($mysql_link);
?>