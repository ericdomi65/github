<?php


/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/

$credits=0;
$rows = 1;
$reload=$_POST['reload'];
$idclient=$_POST['idclient'];
$nom=0;
$prenom=0;
$adresse1=0;
$adresse2=0;
$ville="";
$postal=0;
$pseudonyme=0;
$password=0;
$email=0;
$message="";

$numChamps=0;
// L19
if($reload == 'true')
$campagne = $_POST['old_campagne'];
else $campagne = $_POST['campagne'];

$codesession=$_POST['codesession'];
$ColMob=$_POST['colmob'];
echo '<html><head></head><body>';
if(empty($ColMob)) { echo " oubli : la colonne du mobile!. Faire retour arriere.";exit();}

$message=$_POST['message'];

$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/


//identifier client par rapport a son pseudo et mdp;
$requete = "SELECT * FROM utilisateurs WHERE idclient=$idclient";
$result = mysqli_query($mysql_link,$requete); 
if(!$result)	{  
echo "warning : impossible  de vous identifier:Faites retour";
echo " puis recommmencer: ".mysqli_error($mysql_link);
	exit(); }
$voir = mysqli_fetch_array($result);
// L43
$credits= $voir['credits'];
$litige = $voir['litige'];

echo " Verification du statut de votre compte...";

if($litige =='true') { echo " N'oubliez pas de regler votre litige avec nous avant de demarrer une autre campagne";
	      echo " <BR> A bientot. Service clientelle : 0950.55.3333"; exit();
	    }	
echo "...OK.<BR>";


$conn_id = ftp_connect('localhost');
$login_result = ftp_login($conn_id,'matrix65300','YouKia_2210');
if ((!$conn_id) || (!$login_result)) {
         echo "envoi-campagne.php : La connexion FTP a échoué !"; 
         echo "Tentative de connexion au serveur $ftp_server pour l'utilisateur anonyme";
         exit;     }
 else {echo "Connexion au serveur $ftp_server, pour l'utilisateur anonyme";    }

echo "<BR>changement de repertoire";
ftp_chdir($conn_id,"../campagnesbtoc/");


// L67
   if(!isset($_FILES['userfile']['name']))
	{

echo '<p align=center>aucun fichier a ete uploaded.</P></body></html>'; exit;
	}

else echo " Téléchargement du fichier, veuillez patienter...";
$uploaddir = "./";
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
$fichier=basename($_FILES['userfile']['name']);
   if($fichier['size'] > 10000){
     echo '<p align=center> Nous ne pouvons enregistrer le fichier :taille trop importante '.$fichier['size'].' (>10000Ko).</P></body><html>';
		      exit;}
echo "<BR> Téléchargement de fichier";


if($reload=='true')
if(!ftp_get($conn_id,$uploadfile,$_FILES['userfile']['name'],FTP_TEXT))
	echo " votre fichier est nouveau...!";
	else echo " <BR> La version precedente va etre remplacée.";
$upload = ftp_put($conn_id,$uploadfile,$_FILES['userfile']['tmp_name'],FTP_TEXT);

	if (!$upload) 	{ 
        echo "<BR>Le chargement FTP a échoué! Recommencez...";exit();    
			}
	else {  echo "<BR>Chargement de".$_FILES['userfile']['name']." réussi.";    }
ftp_close($conn_id); 

$pseudonyme=$_POST['pseudonyme'];
$password=$_POST['password'];
$nom=$_POST['nom'];
$prenom=$_POST['prenom'];
$adresse1=$_POST['adresse1'];
$adresse2=$_POST['adresse2'];
$postal=$_POST['postal'];
$ville=$_POST['ville'];
$email=$_POST['email'];

if(!empty($pseudonyme))$numChamps++;else $pseudonyme=0;
if(!empty($password))$numChamps++;else $password =0;
if(!empty($nom))$numChamps++;else  $nom=0;
if(!empty($prenom))$numChamps++;else  $prenom=0;
if(!empty($adresse1))$numChamps++;else $adresse1=0;
if(!empty($adresse2))$numChamps++;else  $adresse2=0;
if(!empty($postal))$numChamps++;else $postal=0;
if(!empty($ville))$numChamps++;else  $ville=0;
if(!empty($email))$numChamps++;else  $email=0;
echo "Enregistrement des variables de la campagne...:<BR>";
$TabDate=getDate();
$chaineDate=$TabDate['year']."-".$TabDate['mon']."-".$TabDate['mday']." ".$TabDate['hours'].":".$TabDate['minutes'].":".$TabDate['seconds'];

if($reload=='false'){
$requete ="INSERT INTO campagne (idclient,nom_campagne,date_creation,message,fichier,mobile,nom,prenom,";
$requete .="pseudonyme,password,adresse1,adresse2,postal,ville,email) VALUES (";
$requete .="$idclient,'$campagne','$chaineDate','$message','$fichier',$ColMob,$nom,$prenom,$pseudonyme,$password,";
$requete .="$adresse1,$adresse2,$postal,$ville,$email);";


} //fin if(!reload)

// MISE A JOUR paramettres colonne
if (reload=='true')	{
// update possible des parametres colonne
$requete="UPDATE campagne set date_creation='$chaineDate',fichier='$fichier'";
if($numChamps >=1){
$requete.=",mobile=$ColMob,nom=$nom,prenom=$prenom,pseudonyme=$pseudonyme,password=$password,adresse1=$adresse1,adresse2=$adresse2,postal=$postal,";
$requete .="ville=$ville,email=$email WHERE idclient=$idclient and nom_campagne='$campagne';";
		}

			} // if reload =true

$result = mysqli_query($mysql_link,$requete); 

if(!$result)	{  
echo "warning : impossible  Enregistrer parametres colonnes :Faites retour";
echo " puis recommmencer: ".mysqli_error($mysql_link);
	exit(); }
echo "..............................OK<BR>";

echo " analyse du fichier pour la colonne mobile:...<BR>";
//84

echo " Ouverture fichier de données téléchargé...";
$handle = fopen("http://kcb/campagnesbtoc/".$fichier, "r");
if(($data = fgetcsv($handle, 1000, ";")) != FALSE)
	{ //88
echo "....OK!<BR>";
$num = count($data);
$numChamps++; // ajout de 1 pour tenir compte de $colmob
echo "<p> $num champs &agrave; la  ligne 1: <BR>";
echo "    $numChamps  champs  renseignés dans le formulaire:<BR>";
if($numChamps > 1 && $num != $numChamps)echo "...probleme de parametrage : possibilite d'erreur dans verification cohesion du champs telephone...</P>";

do{
$num = count($data);
for ($c=0,$j=1; $c < $num; $c++,$j++)
if($j==$ColMob)
	if(!is_numeric($data[$c]))	{

echo " La colonne mobile du fichier uploadé ne colle pas avec le rang de la variable specifiée.";

if(!is_null($data[$c]))	{ if(is_string($data[$c]))
echo "<BR> Nous avons : colonne mobile specifiée = ".$ColMob." => chaine alphanumérique";
else
echo "<BR> Nous avons : colonne mobile specifiée = ".$ColMob." => données inconnues";
			} // if !is_null
			else 
echo "<BR> Nous avons : colonne mobile specifiée = ".$ColMob." => chaine de valeur nulle";
echo " <BR> a la ligne $rows.";
//exit();				
}
$rows++;
	} while(($data = fgetcsv($handle, 1000, ",")) != FALSE);
} // fin if
else {echo "...impossible ouvrir fichier: faites retour pour recommencer!Merci!";exit();}
echo "... analyse terminée !.<BR>";

fclose($handle);
mysqli_close($mysql_link);

echo "<script Language='Javascript'>alert(\"".$rows." enregistrements verifiés\");</script>";
echo "<BR> Mesure de verification du credit par rapport au nombres de clients:...<BR>";

if($credits < $rows)	{echo " votre credit de SMS ( $credits )est insuffisant de :".($rows-$credits);
			 echo " pour envoyer des SMS a tout le monde!<BR>";

echo "vous pouvez <A target=_blank href='http://sms.web-diffusion-france.com/ventes-sms/paiements.html'>cliquer ici";
echo " pour acheter des SMS</A><BR>";
			}
else { 
$nbcamp=($rows / $credits);

if($nbcamp >= 1)
"echo vous avez assez de credits pour faire $nbcamp campagnes avec ce fichier";

echo "vous pouvez <A target=_blank href='http://sms.web-diffusion-france.com/ventes-sms/paiements.html'>cliquer ici";
echo " pour acheter des SMS</A><BR>";
}
echo "FIN, AU REVOIR!";
mysqli_close($mysql_link);
?>