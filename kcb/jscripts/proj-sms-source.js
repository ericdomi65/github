function verifSubmit(){
var Themeobjet="";
var login=document.getElementById("pseudo").value;
var passe=document.getElementById("passe").value;

reponse=verifLoginMdp(login,passe);
statut=reponse.substring(0,3);
reponse=reponse.substring(3,reponse.length);

alert(reponse);
var loginmdp=document.getElementById("loginmdp");
if (statut=="non")
	{
loginmdp.style.color="red";
passe.focus();
return;
	}
else loginmdp.style.color="green";

if(document.all)	{

Themeobjet=document.all.theme.value;

document.all.theme.value=accoleAntiSlash(Themeobjet,0);
alert(document.all.theme.value);
		}
else	{
var Themeobjet=document.getElementById("theme");
Themeobjet.innerHTML=accoleAntiSlash(Themeobjet.innerHTML,0);
	}
with(document.identification)submit();
} // fin fonction

function verifLoginMdp(log,pas){
var i=0;

var login=encodeURIComponent(log);
var passe=encodeURIComponent(pas);
// verif unicite paire login mot de passe
var ok=false;
var xhr=null;
  if (window.XMLHttpRequest)
    { 
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) 
    {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
/*
	try{
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	}	
	catch(e){ alert("erreur 1= "+e);}
*/
	xhr.open("GET","../php/proj-sms-login-mdp.php?login="+login+"&passe="+passe,false);

	try{
	xhr.send(null); 
	}
	catch(e){ alert("erreur 2 = "+e);}
while(i < 10000){
	
         if(xhr.readyState  == 4)
         {

              if(xhr.status  == 200)
	{ 
	return xhr.responseText;
	}else 	{
	alert("erreur en récupération de données: "+xhr.status);return "non.probleme technique";
		}
         } // fin if(xhr.readyState  == 4)
//	else alert("xhr.readyState = "+xhr.readyState);
i++
	}; // fin while
}// fin fonction
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