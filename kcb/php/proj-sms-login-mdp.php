<?PHP
$voir=null;
$login=$_REQUEST['login'];
$passe=$_REQUEST['passe'];
$i=0;
	/*****************************************************************/

$url="localhost:3306";
$database="kcb_data_struct";
$mdp="mot-de-passe"
/*****************************************************************/
$mysql_link=mysqli_connect($url, $database, $mdp) or die ('I cannot connect to the database because: ' . mysqli_error($mysql_link));

/*****************************************************************/
 
	$requete = "SELECT * FROM identite WHERE  passe='$passe' OR pseudo='$login'";
	$result = mysqli_query($mysql_link,$requete);
//	echo("<BR>$requete");
	if($result != null){
		$numrows=mysqli_num_rows($result);
//		echo("<BR>numrows=$numrows");
	if($numrows != 0 )	{

	while ($i< $numrows)	{
		$voir = mysqli_fetch_array($result);
		$mdp=$voir['passe']; $pseudo=$voir['pseudo'];

	if($mdp==$passe){		echo ("non.votre mot de passe est déja pris. Veuillez bien choisir un mot de passe unique");exit();}

	if($pseudo==$login){	echo ("non.votre login est déja pris. Veuillez bien choisir un login unique");exit();}

	$i++;
				}
			}
			else echo ("oui.votre login est disponible.$login et $passe");exit();
		} // if($result != null)
		else 	echo ("oui. impossible de verifier unicite paire login/mot de passe");
mysqli_close($mysql_link);
	exit();
?>