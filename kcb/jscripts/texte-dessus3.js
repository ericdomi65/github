


/*
Script messag toujours visible-
© Dynamic Drive (www.dynamicdrive.com)
Pour le code source complet, les instructions d'installation,
des centaines d'autres scripts DHTML et les modalités d'utilisation
visitez dynamicdrive.com
*/

//Saisissez le messag que vous souhaitez afficher, incluant les balises HTML
var messag='<b><font color=000000 size=5><a href="http://gratuitannonces-online.com">visitez gratuitannonces.fr maintenant!</A></font></b>'

//saisissez le nom de la couleur d'arrière-plan du messag à utiliser
var backgroundcolo="cyan"

//tapez 0 pour toujours afficher, 1 pour afficher durant une période donnée, 2 pour affichage en mode aléatoire
var displaymod=1

//si displaymod est paramétré pour afficher durant une période donnée, inscrivez cette dernière ci-dessous (1000=1 s)
var displayduratio=50000

//tapez 0 pour un messag non-clignotant, 1 pour un messag clignotant
var flashmod=1
//si le messag est paramétrer à clignotant, saisissez le nom de la couleur de clignotement ci-dessous
var flashtocolo="purple"
var which=0
var largeur=document.all?parseInt(document.body.clientWidth):parseInt(window.innerWidth);
var hauteur=document.all?parseInt(document.body.clientHeight):parseInt(window.innerHeight);
///////////////ne rien modifier sous cette ligne////////////////////////////////////////


function regenerate(){
window.location.reload()
}

function regenerate2(){
if (document.layers)
setTimeout("window.onresize=regenerate",400)
}


function _display2(){
if (document.layers){
if (topms.visibility=="show")
topms.visibility="hide"
else
topms.visibility="show"
}
else if (document.all){
if (topms.style.visibility=="visible")
topms.style.visibility="hidden"
else
topms.style.visibility="visible"
setTimeout("_display2()",Math.round(Math.random()*10000)+10000)
}
}

 function _flash(){
if (which==0){
if (document.layers)
topms.bgColor=flashtocolo
else
topms.style.backgroundcolo=flashtocolo
which=1
}
else{
if (document.layers)
topms.bgColor=backgroundcolo
else
topms.style.backgroundcolo=backgroundcolo
which=0
}
}


if (document.all){
document.write('<span id="topms" style="position:absolute;visibility:hidden">'+messag+'</span>')
}


function _logoit(){

document.all.topms.style.left=document.body.scrollLeft+(largeur/2)+(largeur/5)-document.all.topms.offsetWidth/2
document.all.topms.style.top=document.body.scrollTop+(hauteur/2)-document.all.topms.offsetHeight-4
}


function _logoit2(){
topms.left=pageXOffset+window.innerWidth/2-topms.document.width/2
topms.top=pageYOffset+window.innerHeight-topms.document.height-5
setTimeout("_logoit2()",90)
}

function setmessag(){
document.all.topms.style.left=document.body.scrollLeft+(largeur/2)+(largeur/5)-document.all.topms.offsetWidth/2
document.all.topms.style.top=document.body.scrollTop+(hauteur/2)-document.all.topms.offsetHeight-4
document.all.topms.style.backgroundcolo=backgroundcolo
document.all.topms.style.visibility="visible"
if (displaymod==1)
setTimeout("topms.style.visibility='hidden'",displayduratio)
else if (displaymod==2)
_display2()
if (flashmod==1)
setInterval("_flash()",1000)
window.onscroll=_logoit
window.onresize=new Function("window.location.reload()")
}


function setmessag2(){
topms=new Layer(window.innerWidth)
topms.bgColor=backgroundcolo
regenerate2()
topms.document.write(messag)
topms.document.close()
_logoit2()
topms.visibility="show"
if (displaymod==1)
setTimeout("topms.visibility='hide'",displayduratio)
else if (displaymod==2)
_display2()
if (flashmod==1)
setInterval("_flash()",1000)
}



if (document.layers)
window.onload=setmessag2;
else if (document.all)
window.onload=setmessag;