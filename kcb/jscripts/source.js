var texte="<div align=center style='font-size:9pt;border:outset;border-width:1;border-color:cyan'>Tu peut envoyer ces videos, une &agrave; une, sur le mobile d'un ami en tapant :<BR>'sendngo video mobile fichier extension' au 81077.<BR><B><U>mobile</U></B> correspond au numero de mobile de ton correspondant (ex: 0612345678)<BR><B><U>fichier</U></B> correspond au nom numerique de fichier dans la marge de gauche (ex: 228)<BR><B><U>extension</U></B> correspond au format que tu désire pour que le fichier soit lisible sur le mobile voulu. --->les formats disponibles sont : mp4 3gp avi</div>";
var fichier=new Array();
var titre=new Array();
var mp4=new Array();
var gp3=new Array();
var avi=new Array();
function response()
{

var embedBefore = "<embed src='http://www.culturepub.eu.com/player/player.swf' width='200' height='100' bgcolor='undefined' allowscriptaccess='always' allowfullscreen='true' flashvars='file=http://www.culturepub.eu.com/flv/"; 
var embedAfter = "&plugins=viral-2&playlist=over&repeat=always&shuffle=true'/>";
var nomvideo="";
var TabDetailvideo=null;
//commence la creation d'une table d'affichage
var table = "<table align=center width=50% border=1>";
// extrait chaque nom de video flv du tableau. Structure de lenregistrement (5 champs) : nomvideo.flv:chaine;titre:chaine;mp4:0/1;3gp:0/1;avi:0/1;
var cadre=document.getElementById("cadre");
	//on fait juste une boucle sur chaque element "donnee" trouvé
	for(i=0; i < fichier.length;i++){
	TabDetailvideo=new Array(fichier[i],titre[i],mp4[i],gp3[i],avi[i]);
	table+="<TR><TD align=left style='font-size:7pt;' width='60px'>";
//	alert("ok 2 ");
	for(j=0; j < 5; j++){
switch(j){

case 0:
table +="<B><U>Fichier:</U></B>";break;

case 1:
table +="<B><U>Titre:</U></B>";break;

case 2:
table +="<B><U>mp4:</U></B>";break;

case 3:
table +="<B><U>3gp:</U></B>";break;

case 4:
table +="<B><U>Avi:</U></B>";break;
} // fin switch
table +=TabDetailvideo[j]+"<BR>";
			} // fin for 2 
nomvideo = TabDetailvideo[0];
table +="</TD><TD align=center valign=middle width='200px'>";
table +=embedBefore+nomvideo+embedAfter+"</TD></TR>";
	} // fin for 1

/*
document.open();
document.write(texte);
document.write(table);
document.close();
*/
cadre.innerHTML=texte;
cadre.innerHTML+=table;
}
