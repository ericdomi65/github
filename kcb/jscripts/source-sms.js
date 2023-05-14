 /*

test pour modifier le cache d'appache
ecrire du contenu dans le fichier script

*/
var TabloListesMobiles=new Object();
var TabLibelles=new Array();
var TabRef_Listes=new Array();

function ListeMobiles(ref_liste){
this.TabMobile=new Array();
this.flag=true;
this.ref_liste=ref_liste;
			} // fin function ListeMobiles

function ajouteMobile(ref_liste,libelle,mobile)	{
var tab=new Array();
var trouve=false;
if(TabloListesMobiles[libelle]){

tab=TabloListesMobiles[libelle].TabMobile;
trouve=false;
longueur=tab.length;
for(i=0; i < longueur;i++)
if(tab[i]==mobile){trouve=true;tab[i]=0;break;}
if(!trouve){tab[tab.length]=mobile;TabloListesMobiles[libelle].flag=true;}
else	{
trouve=false;
for(i=0; i < tab.length;i++)if(tab[i]!=0){trouve=true;break;}
if(!trouve)TabloListesMobiles[libelle].flag=false;
	}
TabloListesMobiles[libelle].TabMobile=tab;
alert("2 tablo= "+TabloListesMobiles[libelle].TabMobile);
			} // fin if 
else	{	//creation Objet complexe
TabloListesMobiles[libelle]=new ListeMobiles(ref_liste);
longueur=TabloListesMobiles[libelle].TabMobile.length;
TabloListesMobiles[libelle].TabMobile[longueur]=mobile;
//TabLibelles[TabLibelles.length]=libelle;
//TabRef_Listes[TabRef_Listes.length]=ref_liste;
alert("1 tablo= "+TabloListesMobiles[libelle].TabMobile);
	}
					} //fin function ajouteMobile


function supprimerMobiles()	{
var divForm=null;
var trouve=false;
divForm=document.getElementById("supprimer");
divInfo=document.getElementById("info");
//divForm.innerHTML="<form action=\"./supprimerMobiles.php\" METHOD=\"POST\" NAME=\"supprimer\">";
for(libelle in TabloListesMobiles){

if(TabloListesMobiles[libelle].flag==true){
	trouve=true;
 TabLibelles[ TabLibelles.length]=libelle;
 TabRef_Listes[ TabRef_Listes.length]=TabloListesMobiles[libelle].ref_liste;
divForm.innerHTML+="<input type=hidden name="+libelle+"[] value="+TabloListesMobiles[libelle].TabMobile+">";
				} // fin if
			} // fin for

divForm.innerHTML+="<input type=hidden name=\"libelle[]\" value="+TabLibelles+">";
divForm.innerHTML+="<input type=hidden name=\"ref_liste[]\" value="+ TabRef_Listes+">";
//divForm.innerHTML+="</form>";
if(trouve)
with(document.supprimer)	{
alert(divInfo.innerHTML);
return;				} // fin with
submit();


			} // fin fonction


var tabjour=new Array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
var tabmois=new Array("Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre");

var TabRows=new Array();
var nbchar = 159;
var LongueurMess=0;
var campagne=false;
var wdf=null;
var doc=null;
var TabTimer=new Array();
var RefId=new Array();
var DateEnvoi=new Array();
var TabNomResp = new Array();  
var TabPrenomResp= new Array();




function addelDigit(){
var textemessage=document.getElementById("textemessage");
//alert(textemessage.value);
var message=textemessage.value;
var LongueurMess=message.length;

if( LongueurMess >320)	{
	alert("message trop long");
	textemessage.value=message.substring(0,(LongueurMess-1));
	return;			
			}

var montruc=document.forms[0];
	montruc.compteur.value = nbchar-(LongueurMess);
		return;}

function VerifSubmit(num){
var textemessage=document.getElementById("textemessage");
with(document.forms[0]){

if(isNaN(telephone.value) ){if(!confirm("Numero portable doit etre une suite de chiffres sans caracteres\n Exemple: 671481086"))return;}
if((telephone.value.length <9)){if(!confirm("Numero portable incomplet : "+telephone.value.length+" chiffres au lieu de 9"))return;}
if(!campagne && textemessage.value.length ==0){alert("Message  vide!!!");return;}

alert(textemessage.value);

textemessage.value=accoleAntiSlash(textemessage.value, 0);

if (textemessage.value.length >0)
{
if(!confirm(textemessage.value))return;
}
else {if(!confirm("Le message sera téléchargé a partir de votre paramétrage"))return;}

submit();		}
return;
}


function accoleAntiSlash(chaine, i){
		
		var copie=null;

		if((i=chaine.indexOf('\'',i) )>0){
			if(chaine.charAt(i-1)!='\\')
			{
		copie=chaine.substring(0,i)+"\\'" +chaine.substring(i+1)+" ";
		return accoleAntiSlash(copie,i+2);
			}else return accoleAntiSlash(chaine,i+2);
		}
		else return chaine;
	}
		
function afficheDetailsCmpgn(numCampagne){


var numCampagne=numCampagne-1;
var chaine="Campagne N°"+ numCampagne +" : "+ TabCampagne[numCampagne][0] +"\n";
chaine+=" nom Fichier = "+TabCampagne[numCampagne][1]+"\n"  +
	" Rang du mobile = "+ TabCampagne[numCampagne][2];
alert(chaine);
}




function afficheDetailsHCmpgn(numCampagne){
var chaine="";
var afficher=false;
var clef=0;
var apresIndex=1;
var datefinBool=false;
numCampagne=parseInt(numCampagne);

chaine = histo_Campagnes[numCampagne].nom;
chaine +=" nom campagne\n";

chaine += histo_Campagnes[numCampagne].date;
chaine +=" date édition\n";

chaine += histo_Campagnes[numCampagne].nbmobiles;
chaine +=" Nombre de destinataires\n";

chaine += histo_Campagnes[numCampagne].nbsms;
chaine +=" nombre Total de SMS\n";

chaine += histo_Campagnes[numCampagne].expired;
chaine +=" message expiré\n";

chaine += histo_Campagnes[numCampagne].failed;
chaine +=" transmis impos au niveau opérateur\n";


chaine += histo_Campagnes[numCampagne].unknown;
chaine +=" Erreur inconnue\n";

chaine += histo_Campagnes[numCampagne].queued;
chaine +=" queued\n";

alert(chaine);
if(histo_Campagnes.length -1> numCampagne)	{
apresIndex+=numCampagne;
chaine=histo_Campagnes[apresIndex].flag==1? "du"+ histo_Campagnes[numCampagne].date+" au "+histo_Campagnes[apresIndex].date:"à partir de: "+histo_Campagnes[numCampagne].date;
datefinBool=true;
					}
afficher=confirm("Voulez-vous afficher les messages relatifs à cet enregistrement  historique campagne?\n"+chaine);

if(afficher)

with(document.rafraichir)	{
motclef.value=histo_Campagnes[numCampagne].motclef;
datedeb.value=histo_Campagnes[numCampagne].date;
if(datefinBool)datefin.value=histo_Campagnes[apresIndex].date;
else datefin.value="";
ref_liste.value=histo_Campagnes[numCampagne].ref_liste;
campagne.value=histo_Campagnes[numCampagne].nom;
submit();
			}

}
//jour=ddate.getDay();
//jour=tabjour[jour];
//mois=tabmois[mois];

function afficheDate(obj,periode){

ddate=new Date();

jourmois=ddate.getDate();
mois=ddate.getMonth();
mois++;
annee=ddate.getFullYear(); 

heures=ddate.getHours();
minutes=ddate.getMinutes();
if(obj.value=="")
switch(periode){

case "annee" : obj.value=annee;break;

case "mois" : obj.value=mois; break;

case "jour" : obj.value=jourmois;break;

case "heure" : obj.value=heures;break;

case "minutes" : obj.value=minutes;
}
}

function incrementeHeure(booleen){

var timeVal=0;
var heureVal=0;
var heure=document.all.heure;
var minutes=document.all.minutes;


if(heure.value ==""){
afficheDate(heure,"heure");
afficheDate(minutes,"minutes");
return;}

	
if(booleen)	{
	timeVal=parseInt(minutes.value) + 15;
	heureVal=parseInt(heure.value)
	if(timeVal > 45)	{
	timeVal=0;
	heureVal=heureVal +1 < 24 ?++heureVal:23;
			}
	minutes.value=timeVal;
	heure.value=heureVal;
	
		} // if booleen

else		{
	heureVal=parseInt(heure.value)
	timeVal=parseInt(minutes.value) - 15;
	if(timeVal < 15)	{
	timeVal=0;	
	heureVal=heureVal - 1 > 0 ?--heureVal:0;
			}
	minutes.value=timeVal;
	heure.value=heureVal;
	
		} // if booleen

} // FIN FONCTION

function afficheChampsDate(){

var groupedate=document.getElementById("groupedate");

//alert(groupedate);

if(groupedate.style.visibility=="visible")
groupedate.style.visibility="hidden";
else groupedate.style.visibility="visible";
}

function modifActionLM(booleen){
// document.envoisms.action="http://sms.web-diffusion-france.com/php/envoismsCamp.php";
if(booleen){
var camp=document.getElementById("campagne");
if(camp.selectedIndex >0){alert("Une liste de numeros nominative est deja selectionnee, \nveuillez choisir entre les 2 type de listes");return;}
document.envoisms.action="./envoismsCamp.php";
document.all.BoutonCampPerso.disabled=true;
}else
{
document.envoisms.action="./envoisms.php";
document.all.BoutonCampPerso.disabled=true;
alert("envoi a l'unité selectionné");
}
}
function modifAction(booleen){

if(booleen){
document.envoisms.action="./envoismsCamp.php";
document.all.BoutonCampPerso.disabled=false;
//campagne=true;
}else
{
document.envoisms.action="./envoisms.php";
document.all.BoutonCampPerso.disabled=true;
var camp=document.getElementById("campagneLM");
var campagne=document.getElementById("campagne");
if(camp.selectedIndex ==0 && campagne.selectedIndex ==0)
alert("envoi a l'unité selectionné");

}

}
function afficheCampEtModifAction(IndexValue)
{

if (IndexValue > 0) 		{
var camp=document.getElementById("campagneLM");
if(camp.selectedIndex >0){alert("Une liste de numeros aleatoires est deja selectionnee, \nveuillez choisir entre les 2 type de listes");return;}
afficheDetailsCmpgn(IndexValue);
modifAction(true);return;	}

else	{
modifAction(false);
//alert("modifAction(false) IndexValue = "+IndexValue);
return;

	}
}


function ajouteParam(){

var textemessage=document.getElementById("textemessage");


var myQuery = textemessage;
//alert(" myQuery = "+myQuery);
var myListBox = document.envoisms.parametres;
    if(myListBox.options.length > 0) {
	var chaineAj = "";

for(var i=0; i<myListBox.options.length; i++) 		{
	if (myListBox.options[i].selected)	{
		chaineAj= myListBox.options[i].value;
				}	}
            myQuery.focus();
//            myQuery.outerHTML ="<textarea name='textemessage' id='textemessage'>"+myQuery.outerText+ "["+chaineAj+"]"+"</textarea>";
		myQuery.innerHTML +="["+chaineAj+"]";		
     } //  if(myListBox.options.length > 0)

			    } // fin fonction
function changeActionOuvrePerso(codese)
{
 var myListBox = document.envoisms.campagne;
with(document.envoisms)
			{
action='./parametrageCamp-Perso.php?codesession='+codese+"&campagne=";


    if(myListBox.options.length > 0) 
				{
	var chaineAj = "";

for(var i=0; i<myListBox.options.length; i++) 		{
	if (myListBox.options[i].selected)	{
		action +=myListBox.options[i].value;
		//alert("myListBox.options[i].value = "+myListBox.options[i].value);
		break;
		
		
						}	}
				} //if(myListBox.options.length > 0) 
submit();
			} // fin with(document.envoisms)
}


function popup(obj,nom,prenom,adresse1,adresse2,ville,postal,pseudonyme,email,password,mobile){
var chaineMessg="";
var longueurTab=arguments.length;

if(obj.checked)	{
TabRows[obj.id]=obj.id;
for(i=1;i < longueurTab; i++)
chaineMessg +=arguments[i]+"\n";
alert(chaineMessg);
		}
else{
TabRows[obj.id]=null;
alert("client exclu de la liste");
}

} // fin fonction.


function initialiseTabRows(rows,NbCarMax){
for(i=0; i <= rows; i++)
TabRows[i]=null;
alert(rows + " enregs initialisés\nTaille maximum enregistrement fonction des champs selectionnés :"+NbCarMax);return;
}


function VerifSubmitCampPerso(){
var textemessage=document.getElementById("textemessage");
var result="";
result=TabRows.join('-');
alert(result);
with(document.forms[0]){
champsRowsInclus.value=result;
textemessage.value=accoleAntiSlash(textemessage.value, 0);

if (textemessage.value.length >0)
{
if(!confirm(textemessage.value))return;
}
else {if(!confirm("Le message sera téléchargé a partir de votre paramétrage"))return;}
submit();
}
}
function toutSelectionner(nbRegs){
var obj=null;
var i =0;
if(document.forms[0])alert(document.forms[0].name);else { alert ("probleme arbre DOM");return;}
if(document.all.box.checked)	{	

with(document.forms[0])	 {
champsRowsInclus.value=0;}

for(i=0; i< nbRegs; i++){
obj=document.getElementById(i);
obj.checked=true;
TabRows[i]=i;
			} // fin for;

				} // if document.all
else{
with(document.forms[0])	 {
champsRowsInclus.value="";}
for(i=0; i< nbRegs; i++){
obj=document.getElementById(i);
obj.checked=false;
TabRows[i]=null;
			}// fin for
    } // fin else
}



function messageConsole(message){
if(doc)doc.writeln(message);
else alert("prob avec doc");
}


function InitCpt(i,dateenvoi,nomResp,prenomResp){

RefId[i]=i;
DateEnvoi[i]=dateenvoi;


TabNomResp[i]=nomResp;
TabPrenomResp[i]=prenomResp;
//apelle fonction recursive d'affichage
afficheCpt(i);
} // fin function


function afficheCpt(j){		

var aujour=new Date();
var obj=document.getElementById(j);
var TabDateHeure=DateEnvoi[j].split(" ");
var i=0;
//alert("date="+TabDateHeure[0]+" heure="+TabDateHeure[1]);

var TabDate=TabDateHeure[0].split("-");
var TabHeure=TabDateHeure[1].split(":");
jour=aujour.getDate();
mois=aujour.getMonth();
mois++;
annee=aujour.getFullYear(); 

heures=aujour.getHours();
minutes=aujour.getMinutes();
secondes=aujour.getSeconds();


difannee=TabDate[0]-annee;
difmois=TabDate[1]-mois;
difjour=TabDate[2]-jour;
difheures=TabHeure[0]-heures < 0 ? 24 + (TabHeure[0]-heures) : TabHeure[0]-heures;
difminutes=TabHeure[1]-minutes < 0 ? 60 + (TabHeure[1]-minutes) : TabHeure[1]-minutes;
difsecondes=TabHeure[2]- secondes < 0 ? 60 + (TabHeure[2]- secondes) :TabHeure[2]- secondes;


difheures = TabHeure[1]-minutes < 0 ? --difheures : difheures;
difminutes = TabHeure[2]- secondes < 0 ? --difminutes : difminutes;



if(difannee==0 && difmois==0 && difjour ==0 && difheures==0 && difminutes==0 && difsecondes==0)
{
alert("demarrage de la campagne : " + TabNomResp[j]  + " " + TabPrenomResp[j]);
document.forms[j].submit();
}
else{
//affichage du cpt a rebour;
obj.innerHTML=difheures+":"+difminutes+":"+difsecondes+" &nbsp;&nbsp;&nbsp;";
setTimeout('afficheCpt('+j+')',1000);
}
} // fin fonction
 
function afficheInfo(id){
//alert("ok info");
var obj = document.getElementById(id);
//obj.onClick= function(obj){arguments[0].style.visibility="hidden";}
obj.style.visibility="visible";
} // fin fonction

