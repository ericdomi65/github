

/*
Script Image " Pop-up "
© Dynamic Drive (www.dynamicdrive.com)
Pour le code source complet, les instructions d'installation,
des centaines d'autres scripts DHTML et les modalités d'utilisation,
visitez dynamicdrive.com
*/


function downshadow(){
if (event.srcElement.className=="popshadow"){
//alert("downshadow");
tempobject=event.srcElement;
tempobject.filters[0].enabled=false;
}
}


function upshadow(){
return;
}

