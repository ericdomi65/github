<?PHP
$voir=null;
$idclient=$_REQUEST['idclient'];
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

	$requete = "update session set datefin='$chaineDate', heurefin='$chaineHeure' WHERE  idclient=$idclient;";
	$result = mysql_query($requete);
	if($result)
		echo("ok.Vous etes deconnecté à $chaineHeure");
	else	echo("ko.probleme technique:". mysqli_error($link));
	mysqli_close($link);
		exit();
?>
