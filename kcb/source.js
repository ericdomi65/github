
var nbchar = 159;
function addelDigit(){
message=document.all.textemessage.innerText;
if(message.length >160)alert("message trop long");
var montruc=document.forms[0];
montruc.compteur.value = nbchar-(montruc.textemessage.value.length);
;return;}

function VerifSubmit(){
with(document.forms[0]){
if(isNaN(telephone.value)){alert("Numero portable doit etre une suite de chiffres sans caracteres\n Exemple: 0671481086");return;}
if(telephone.value.length <10){alert("Numero portable incomplet : "+telephone.value.length+" chiffres au lieu de 10");return;}
if(textemessage.innerText.length ==0){alert("Message  vide!!!");return;}
submit();		}
return;}
