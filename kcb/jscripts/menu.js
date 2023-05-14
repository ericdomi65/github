


var variable="ceci est un menu concocte par mes soins a partir de mes observations 3ieme modif";


var NumMenu=0, displaySousMenu=false;
var menuLength=6;
var  Tabcode = new Array();
var  TabBoolMenu=new Array();
function effaceMenu(){
	 for(i=0 ; i <Tabcode.length;i++)
		if( TabBoolMenu[i]=true)	{
		 Tabcode[i].style.visibility='hidden';
		 TabBoolMenu[i]=false;
					}
		
		} // fin fonction

function afficheMenu(obj,numMenu, hauteur, largeur, posV, posH){
var code=document.getElementById(numMenu);
//identifie le menu 

// positionnement et largeur du menu à afficher
if(numMenu !=1)
code.style.width=largeur*2;
else code.style.width=largeur*3;
code.style.top=parseInt(hauteur+posV+10);
if(numMenu !=1)
code.style.left=parseInt(posH-(largeur/2));
else code.style.left=1;
code.style.position="absolute";
switch(numMenu){

case 1 :
code.innerHTML="<div align=center  style='background:red;border:solid;border-color:cyan;border-width:2'> Vous pouvez gerer vos coordonnées postales, banquaires<BR> et vos identifiants  ainsi que votre crédit de SMS</div>";
break;

case 2 :

code.innerHTML= "<div  align=center style='background:red;border:solid;border-color:cyan;border-width:2'>Gerer les mots clefs et le renouvellement<BR> ou la suspension de l'abonnement</div>";
break;

case 3:
code.innerHTML="<div  align=center style='background:red;border:solid;border-color:cyan;border-width:2'>Creer une campagne</div>";
break; //L51

case 4:
code.innerHTML="<div  align=center style='background:red;border:solid;border-color:cyan;border-width:2'>Generer des listes aleatoires de mobiles<BR> et les sauvegarder ou les modifier</div>";
 break;

case 5:
code.innerHTML="<div align=center class='fonte' style='background:red;border:solid;border-color:cyan;border-width:2'>Gerer les campagnes envoyees <BR>ou programmées</div>";
 break;


case 6:
code.innerHTML=" <div  class='fonte' style='background:red;solid cyan 12pt'>envoyer campagnes <BR>ou les programmer </div>";
 break;
}

code.style.visibility='visible';
//alert(code.style.top); L 80
//alert(code.style.left);
}


function Menu(numsession){
var titre1="<div class='fonte'><a href='../php/compte.php?codesession="+numsession+"'>Acces au compte</A></div>";
var titre2="<div class='fonte'><a href='../php/abonnement.php?codesession="+numsession+"'> Abonnement</A></div>";
var titre3="<div class='fonte'><a href='../php/creation-campagnes.php?codesession="+numsession+"'>Creation campagnes</A>";
var titre4="<div class='fonte'><a href='../php/gestion-listes-mobiles.php?codesession="+numsession+"'>Listes de mobiles</A></div>";
var titre5="<div class='fonte'><a href='../php/gestion-campagnes.php?codesession="+numsession+"'>Gestion campagnes</a></div>";
var titre6="<div class='fonte'><A href='../php/envoi-campagnes.php?codesession="+numsession+"'>Envoyer campagne</A</div>";



document.write("<table border=1 cellspacing=3 cellpading=0 bgcolor=lightyellow><tr>");

for (j=0;j < menuLength;j++)	{
i=j+1;
titre=eval("titre"+i);
document.write("<TD   style='borderStyle:outset;'align=center valign=top");
document.write("  onmouseover='insetTD(this.style),effaceMenu(),afficheMenu(this,"+i+",this.offsetHeight,this.offsetWidth,this.offsetTop,this.offsetLeft)'");
document.write(" onmouseout='ousetTD(this.style),effaceMenu()' ");
document.write(titre+"</TD>");

			}
document.write("</TR></table>");
for (j=0;j < menuLength;j++)
	{
i=j+1;
document.write("<DIV class=menu id="+i+"></DIV>");
	}

 for(i=0 ; i < menuLength;i++)
 TabBoolMenu[i]=false;

 for(i=0 ; i < menuLength;i++)
 Tabcode[i]=document.getElementById(i+1);

}

function flag(num){


 TabBoolMenu[num]=true;
alert(TabBoolMenu.join('/'));
		}

function insetTD(obj){ //il y a inversion involontaire du nom de fonction choisi et de son contenu

obj.borderStyle='inset';

}


function ousetTD(obj){

obj.borderStyle='none';

}







