<?PHP
$voir=null;
$login=$_REQUEST['login'];
$passe=$_REQUEST['passe'];

$link = mysqli_connect("127.0.0.1", "kcb", "XirTam_65300_-_", "kcb");
/*
ini_get("mysql.default_host")
*/
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}


/*	$mysql_link=mysql_connect(ini_get("mysql.default_host"), "kcb", "XirTam_65300_-_") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db("kcb",$mysql_link); 
*/
	$requete = "SELECT * FROM identite WHERE  passe='$passe' AND pseudo='$login'";
	$result = mysqli__query($requete);
	if($result){
	$numrows=mysqli__num_rows($result);

	if($numrows == 1 )	{

		$voir = mysqli__fetch_array($result);
		$idclient=$voir['identite'];
		echo("ok:$idclient");
		
			}else echo("ko:identifiants non connus dans notre Bdd");

		}else 	echo("ko:Erreur tech".mysqli__error());
		mysqli__close($link);
		exit();

?>