


/*
Script Message toujours visible-
© Dynamic Drive (www.dynamicdrive.com)
Pour le code source complet, les instructions d'installation,
des centaines d'autres scripts DHTML et les modalités d'utilisation
visitez dynamicdrive.com
*/

//Saisissez le message que vous souhaitez afficher, incluant les balises HTML
var message='<b><font color=000000 size=5><a href="http://gratuitannonces-online.com">visitez gratuitannonces.fr maintenant!</A></font></b>'

//saisissez le nom de la couleur d'arrière-plan du message à utiliser
var backgroundcolor="cyan"

//tapez 0 pour toujours afficher, 1 pour afficher durant une période donnée, 2 pour affichage en mode aléatoire
var displaymode=0

//si displaymode est paramétré pour afficher durant une période donnée, inscrivez cette dernière ci-dessous (1000=1 s)
var displayduration=10000

//tapez 0 pour un message non-clignotant, 1 pour un message clignotant
var flashmode=0
//si le message est paramétrer à clignotant, saisissez le nom de la couleur de clignotement ci-dessous
var flashtocolor="purple"


///////////////ne rien modifier sous cette ligne////////////////////////////////////////

function regenerate(){
window.location.reload()
}

var which=0

function regenerate2(){
if (document.layers)
setTimeout("window.onresize=regenerate",400)
}


function display2(){
if (document.layers){
if (topmsg.visibility=="show")
topmsg.visibility="hide"
else
topmsg.visibility="show"
}
else if (document.all){
if (topmsg.style.visibility=="visible")
topmsg.style.visibility="hidden"
else
topmsg.style.visibility="visible"
setTimeout("display2()",Math.round(Math.random()*10000)+10000)
}
}

 function flash(){
if (which==0){
if (document.layers)
topmsg.bgColor=flashtocolor
else
topmsg.style.backgroundColor=flashtocolor
which=1
}
else{
if (document.layers)
topmsg.bgColor=backgroundcolor
else
topmsg.style.backgroundColor=backgroundcolor
which=0
}
}


if (document.all){
document.write('<span id="topmsg" style="position:absolute;visibility:hidden">'+message+'</span>')
}


function logoit(){
document.all.topmsg.style.left=document.body.scrollLeft+document.body.clientWidth/2-document.all.topmsg.offsetWidth/2
document.all.topmsg.style.top=document.body.scrollTop+document.body.clientHeight-document.all.topmsg.offsetHeight-4
}


function logoit2(){
topmsg.left=pageXOffset+window.innerWidth/2-topmsg.document.width/2
topmsg.top=pageYOffset+window.innerHeight-topmsg.document.height-5
setTimeout("logoit2()",90)
}

function setmessage(){
document.all.topmsg.style.left=document.body.scrollLeft+document.body.clientWidth/2-document.all.topmsg.offsetWidth/2
document.all.topmsg.style.top=document.body.scrollTop+document.body.clientHeight-document.all.topmsg.offsetHeight-4
document.all.topmsg.style.backgroundColor=backgroundcolor
document.all.topmsg.style.visibility="visible"
if (displaymode==1)
setTimeout("topmsg.style.visibility='hidden'",displayduration)
else if (displaymode==2)
display2()
if (flashmode==1)
setInterval("flash()",1000)
window.onscroll=logoit
window.onresize=new Function("window.location.reload()")
}


function setmessage2(){
topmsg=new Layer(window.innerWidth)
topmsg.bgColor=backgroundcolor
regenerate2()
topmsg.document.write(message)
topmsg.document.close()
logoit2()
topmsg.visibility="show"
if (displaymode==1)
setTimeout("topmsg.visibility='hide'",displayduration)
else if (displaymode==2)
display2()
if (flashmode==1)
setInterval("flash()",1000)
}



if (document.layers)
window.onload=setmessage2;
else if (document.all)
window.onload=setmessage;
mydynAnimation();

/*polo=setTimeout('glisser()',50);*/
