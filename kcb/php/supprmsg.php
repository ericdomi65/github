<?PHP

$clef=$_GET['clef'];

$mysql_link=mysql_connect("hostingmysql99.amen.fr", "cm188797", "XirTam_65300") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db("cm188797",$mysql_link); 

$requete = "DELETE  FROM mesg_prosp WHERE clef=$clef;";
$result = mysql_query($requete); 
if(!$result){echo "impossible supprimer enreg : ".mysql_error(); exit();}
else
echo("$clef");
mysql_close($mysql_link);
exit();
?>