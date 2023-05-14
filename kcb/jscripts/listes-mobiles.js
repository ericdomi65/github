function initAjax(){
var xhr=null;
  if (window.XMLHttpRequest)
    { 
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) 
    {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

return xhr;

}

function ajaxRequete(xhr,url){
	xhr.open("GET",url,false);

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

function creerListe(){
var xhr=null;
var idclient=document.getElementById('idclient').value;
var nbmobiles=document.getElementById('nbmobiles').value;
var libelle=document.getElementById('libelle').value;
xhr=initAjax();
url="../php/envoi-sms-loterie.php?idclient="+idclient+"&nbmobiles="+nbmobiles+"&libelle="+libelle;
/*

envoi-sms-loterie.php?idclient=44295549&nbmobiles=200&libelle=cadeaux_noel
*/
message=ajaxRequete(xhr,url);
if(isNaN(parseInt(message)))
alert(message+"\nappeller au 09.50.111.333 HB\nou envoyer erreur a services@web-diffusion-france.com\nVeuillez nous excuser pour le désagrement");
else alert(message+" mobiles crées, faire actualiser pour mise a jour page");
return;
}

function allongerListe(idclient,ref_liste,libelle){
var xhr=null;
xhr=initAjax();

url="../php/envoi-sms-loterie.php?idclient="+idclient+"&nbmobiles=200&libelle="+libelle+"&ref_liste="+ref_liste;
message=ajaxRequete(xhr,url);
if(isNaN(parseInt(message)))
alert(message+"\nappeller au 09.50.111.333 HB\nou envoyer erreur a services@web-diffusion-france.com\nVeuillez nous excuser pour le désagrement");
else alert(message+" mobiles crées, faire actualiser pour mise a jour page");
return;
}

function supprimerMsg(clef){
var xhr=null;
xhr=initAjax();

url="../php/supprmsg.php?clef="+clef;
message=ajaxRequete(xhr,url);
if(isNaN(parseInt(message)))
alert(message+"\nappeller au 09.50.111.333 HB\nou envoyer erreur a services@web-diffusion-france.com\nVeuillez nous excuser pour le désagrement");
else alert(" clef suppriméé, faire actualiser pour mise a jour page");
return;
}