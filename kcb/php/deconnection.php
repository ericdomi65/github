<?PHP
$voir=null;
$codesession=$_REQUEST['codesession'];
$aujourd=getDate();
$chaineDate=$aujourd["year"]."-".$aujourd["mon"]."-".$aujourd["mday"];
$chaineHeure=$aujourd["hours"].":".$aujourd["minutes"].":".$aujourd["seconds"];

$link = mysqli_connect(ini_get("mysql.default_host"), "kcb", "XirTam_65300_-_", "kcb");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

	$requete = "update session set datefin='$chaineDate', heurefin='$chaineHeure' WHERE  codesession=$codesession;";
	$result = mysql_query($requete);
	if($result)
		echo("ok.Vous etes deconnecté à $chaineHeure");
	else	echo("ko.probleme technique:". mysql_error());
	mysqli_close($link);
		exit();
?>