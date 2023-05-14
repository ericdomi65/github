var fen=null;
var Theme="";
function  verifSubmitRech(){
var lettre='a';

var secho="",locadonvente="",occaneuf="",depart=0;
var Marqu="", Model="",Theme="";

if(!fen){
fen=window.cadreinterne;
fen.document.open();
}


with(document.recherche){

if(! chose[0].checked && !chose[1].checked && !chose[2].checked && !chose[3].checked && !chose[4].checked && !chose[5].checked && !chose[6].checked && !chose[7].checked &&!chose[8].checked && !chose[9].checked)

{alert("Il faut cocher auto-moto/Objet ou Emploi ou Animal ou Immobilier ou Transaction ");return 0;}

if(! locaventedon[0].checked && !locaventedon[1].checked && !locaventedon[2].checked && !locaventedon[3].checked && !locaventedon[4].checked && !chose[1].checked && !chose[6].checked)

{alert("Il faut cocher location ou vente ou don ou échange ou les 4 ");return 0;}

if(!neufocca[0].checked && !neufocca[1].checked && !neufocca[2].checked && !chose[1].checked && !chose[6].checked )

{alert("Il faut cocher neuf ou occasion  ou les 2");return 0;}

if(isNaN(departement.value)){
lettre=departement.value;if(lettre=="tous")departement.value=0;else

if(lettre.charAt(1)=='A' || lettre.charAt(1)=='a' || lettre.charAt(1)=='B' || lettre.charAt(1)=='b')departement.value=96;

else{ alert("departement non numérique");return;}
}



for(i=0; i <=9; i++)
if(chose[i].checked){secho=chose[i].value;break;}

for(i=0; i <=4; i++)
if(locaventedon[i].checked){ locadonvente=locaventedon[i].value;break;}

for(i=0; i <=4; i++)
if(neufocca[i].checked){ occaneuf=neufocca[i].value;break;}

depart=departement.value;
Marqu=marque.value;
Model=modele.value;
Offdem=offdem.value;
Theme=theme.value;
} // with(document.recherche)

if(!confirm("chose= "+secho+" departement= "+depart+" marque= "+Marqu+" modele= "+Model+" theme= "+Theme))return;

fen.document.write("<html><head></head><body>");
fen.document.write("<form method=post action='http://www.gratuitannonces-online.com/servlet/RechercheOffDemFree'>");

fen.document.write("<input type=hidden name=offdem value="+Offdem+">");

fen.document.write("<input type=hidden name=chose value="+secho+">");

fen.document.write("<input type=hidden name=locaventedon value="+locadonvente+">");

fen.document.write("<input type=hidden name=neufocca value="+occaneuf+">");

fen.document.write("<input type=hidden name=departement value="+depart+">");

fen.document.write("<input type=hidden name=theme value="+Theme+">");

fen.document.write("<input type=hidden name=marque value="+Marqu+">");

fen.document.write("<input type=hidden name=modele value="+Model+">");

fen.document.write("<p align=center>La recherche est en cours...</P>");
fen.document.write("</form></body></html>");fen.document.close();
fen.document.forms[0].submit();

} /* fin fonction.*/







var  collection=document.all.tags('H3');
function changeWeightColor(){
if(flag==true){flag=false;

collection.tai.style.fontWeigth='bold';collection.tai.style.color='teal';
//collection.te.style.fontWeigth='bold';collection.te.style.color='teal';
//collection.tt.style.fontWeigth='bold';collection.tt.style.color='teal';
   }
                else{flag=true;
collection.tai.style.fontWeigth='normal';collection.tai.style.color='cyan';
//collection.te.style.fontWeigth='normal';collection.te.style.color='cyan';
//collection.tt.style.fontWeigth='normal';collection.tt.style.color='cyan';
}/*fin else*/
}/*fin fonction*/




var mabool=true;
var mobool=true;


function showHideMaMo(texte){
with(document.all){

switch(texte){
case 'boxmarque' : 

if(mabool){
marque.value='marque'; automobile.style.visibility='hidden';mabool=false;}
else{ automobile.style.visibility='visible';mabool=true;}

break;

case 'boxmodele' : 
if(mobool){
modele.value='modele';autossmarque.style.visibility='hidden';mobool=false;}
else{ autossmarque.style.visibility='visible';mobool=true;}
break;
} } }



function affiche(texte){

   document.forms[0].imagedyna.src =texte;} 





function checkListValue(texte){

with(document.recherche){
switch(theme.value){

case "localcom" : case "localind" :
if(!chose[9].checked==true)chose[4].checked=true;
imagedyna.src="brique.gif";
automobile.style.visibility="hidden";
moto.style.visibility="hidden";
autossmarque.style.visibility="hidden";
motossmarque.style.visibility="hidden";
modele.value="modele";
marque.value="marque";
break;

case "appart" : case "maison" : case "studio" : 
if(!chose[8].checked==true)chose[3].checked=true;
imagedyna.src="brique.gif";
automobile.style.visibility="hidden";
moto.style.visibility="hidden";
autossmarque.style.visibility="hidden";
motossmarque.style.visibility="hidden";
modele.value="modele";
marque.value="marque";
break;

case "animaux" : 
if(!chose[7].checked==true)chose[2].checked=true;
imagedyna.src="tortue.gif";
automobile.style.visibility="hidden";
moto.style.visibility="hidden";
autossmarque.style.visibility="hidden";
motossmarque.style.visibility="hidden";
modele.value="modele";
marque.value="marque";
break;

case "enseignement" : 
if(!chose[6].checked==true)chose[1].checked=true;
imagedyna.src="reunion.gif";
automobile.style.visibility="hidden";
moto.style.visibility="hidden";
autossmarque.style.visibility="hidden";
motossmarque.style.visibility="hidden";
modele.value="modele";
marque.value="marque";

break;

case "emploi" : 
if(!chose[6].checked==true)chose[1].checked=true;
imagedyna.src="finance.gif";
automobile.style.visibility="hidden";
moto.style.visibility="hidden";
autossmarque.style.visibility="hidden";
motossmarque.style.visibility="hidden";
modele.value="modele";
marque.value="marque";
break;
	
case "automobile" :
imagedyna.src="voiture.gif";
automobile.style.visibility="visible";
autossmarque.style.visibility="visible";
moto.style.visibility="hidden";
motossmarque.style.visibility="hidden";
if(!chose[5].checked==true)chose[0].checked=true;
afficheListAuto();
modele.value="modele";
marque.value="marque";

break;

case "moto" :
moto.style.visibility="visible";
motossmarque.style.visibility="visible";
automobile.style.visibility="hidden";
autossmarque.style.visibility="hidden";
if(!chose[5].checked==true)chose[0].checked=true;
afficheListMoto();
modele.value="modele";
marque.value="marque";
break;

case "transcom" : 
if(!chose[9].checked==true) chose[4].checked=true;
imagedyna.src="pgnmain2.gif";
automobile.style.visibility="hidden";
moto.style.visibility="hidden";
autossmarque.style.visibility="hidden";
motossmarque.style.visibility="hidden";
modele.value="modele";
marque.value="marque";
break;

default : 
automobile.style.visibility="hidden";
moto.style.visibility="hidden";
autossmarque.style.visibility="hidden";
motossmarque.style.visibility="hidden";
modele.value="modele";
marque.value="marque";

} // fin switch
			}	}//fin function

var tabssmAuto=new Array();
var tabssmMoto=new Array();
var tabMoto=new Array('AUCUN');


var tabAuto=new Array('null','Autre','ALFA ROMEO','ALPINA','ALPINE RENAULT','AMC','ARO',
'ASTON MARTIN','AUDI','AUSTIN',
'AUSTIN HEALEY','AUTOBIANCHI','AUVERLAND','BEDFORD','BENTLEY','BERTONE','BMW','BUGATTI','BUICK',
'CADILLAC','CHEVROLET','CHRYSLER','CITROEN','DAEWOO','DAF','DAIHATSU','DALLAS','DANGEL','DATSUN','DE LA CHAPELLE','DE TOMASO','DELAHAYE','DODGE','DONKERVOORT','DONNET','EAGLE','EBRO',
'FERRARI','FIAT','FORD','FSO','GINETTA','GME','GRANDIN','HOMMELL','HONDA','HUMMER','HYUNDAI',
'IATO','INNOCENTI','INTERNATIONAL','ISO','ISUZU','IVECO','JAGUAR','JEEP','KIA','LADA',
'LAMBORGHINI','LANCIA','LAND ROVER','LEXUS','LIGIER','LINCOLN','LOTUS','MAHINDRA','MARUTI',
'MASERATI','MATRA','MAZDA','MEGA','MERCEDES','MG','MINI','MITSUBISHI','MORGAN','MOSKVITCH','MVS','NISSAN','OLDSMOBILE','OPEL','PEUGEOT','PIAGGO','POLSKI','PONTIAC','PORSCHE','PROTON','RENAULT',
'RENAULT V.I','ROLLS ROYCE','ROVER','SAAB','SANTANA','SEAT','SKODA','SMART','SSANGYONG',
'SUBARU','SUZUKI','TALBOT','TEIHOL','TOYOTA','TRIUMPH','TVR','UMM','VENTURI','VOLKSWAGEN',
'VOLVO','YUGO','ZASTAVA');


tabssmAuto[0]=new Array('Autre');
tabssmAuto[1]=new Array('145','146','147','155','156','164','166','33','6','75','90','Alfetta','ES30','Giulia','Giulietta','GTV','Montreal','Roadster','Spider','Sprint','Sud','SZ','Autre'); tabssmAuto[2]=new Array('B10','B11','B12','B3','B6','B7','Série 10','Série 3','Série 5','Série 6','Série 8','Autre'); tabssmAuto[3]=new Array('A310','A610','Alpine','Autre'); tabssmAuto[4]=new Array('BK Pacer','Autre'); tabssmAuto[5]=new Array('10.0','10.1','10.4','Aro 10','Cross Lander','Forester','Spartana','Trapeurs','Autre'); tabssmAuto[6]=new Array('DB7','Lagonda','Vanquish','Vantage','Virage','Autre'); tabssmAuto[7]=new Array('100','200','80','90','A2','A3','A4','A6','A8','Allroad','Cabriolet','Coupé','Coupé GT','Quattro','RS4','S2','S3','S4','S6','S8','TT','TT Roadster','V8','Autre'); tabssmAuto[8]=new Array('Allegro','Maestro','Metro','Mini','Montego','Princess','Autre'); tabssmAuto[9]=new Array('100','3000','Cabriolet','Frogeye','Roadster','Sprite','Autre'); tabssmAuto[10]=new Array('A112','Y10','Autre'); tabssmAuto[11]=new Array('A3','A4','Montez','Autre'); tabssmAuto[12]=new Array('CF2','Fourgon Midi','Autre'); tabssmAuto[13]=new Array('Azur','Brooklands','Continental','Corniche','Eight','L','Mark','Mulsanne','R','S','Silver Cloud','T','Autre'); tabssmAuto[14]=new Array('Freeclimber','Autre'); tabssmAuto[15]=new Array('840','850','1600','1800','2002','M3','M5','Mini Cooper','Mini One','Série 3','Série 3 Compact','Série 3 Touring','Série 5','Série 6','Série 7','Série 8','X5','Z1','Z3','Z3 Roadster','Z4','Z8','Autre'); tabssmAuto[16]=new Array('EB','Autre'); tabssmAuto[17]=new Array('Century','Coupé','Electra','Gran Sport','Heaven','Invicta','Le Sabre','Limited','Park avenue','Riviera','Roadmaster','Skylark','Skywak','Wildcat','Autre'); tabssmAuto[18]=new Array('Allante','Brougham','Calais','Coupé','Deville','Eldorado','Fleetwood','Limousine','Seville','Sixty','Autre'); 
tabssmAuto[19]=new Array('Alero','Belair','Beretta','Biscaine','Blazer','Camaro','Caprice','Chevelle','Chevy','Citation','Corsica','Corvair','Corvette','Coupé','Fleetline','Impala','Malibu','Monza','Ramcharger','Pick-up','S10','Tahoe','Trans Sport','Vega','Autre'); tabssmAuto[20]=new Array('300M','Cherokee','ES','Grand Cherokee','Grand Voyager','Jeep','Le Baron','Neon','New Yorker','PT Cruiser','Saragota','Stratus','Viper','Vision','Voyager','Autre'); tabssmAuto[21]=new Array('2CV','2CV6','AX','AX Entreprise','Axel','Axel Entreprise','Berlingo','Berlingo Fourgon','BX','BX Entreprise','C15','C25','C3','C35','C5','C8','CX','Dyane','Evasion','GS','GSa','Jumper','Jumpy','LN','LNa','Méhari','Picasso','Saxo','Visa','Xantia','XM','Xsara','ZX','Autre'); tabssmAuto[22]=new Array('Espero','Korando','Lanos','Leganza','Matiz','Musso','Nexia','Nubira','Rezzo','Autre'); tabssmAuto[23]=new Array('3t5','400','Autre'); tabssmAuto[24]=new Array('Applause','Charade','Domino','Feroza','Gran Move','Hijet','Move','Rocky','Rocky Wagon','Sirion','Terios','YRV','Autre'); tabssmAuto[25]=new Array('Pick-up','Autre'); tabssmAuto[26]=new Array('504','505','C15','C25','J5','Autre'); tabssmAuto[27]=new Array('100','120','160','220','260','280','Berline','Cedric','Coupé','Laurel','Violet','Autre'); tabssmAuto[28]=new Array('55','57','Atalante','PC12','Type 55 roadster','Autre'); tabssmAuto[29]=new Array('Deauville','Lonchamps','Pantera','Autre'); tabssmAuto[30]=new Array('Berline','Coach','Limousine','Autre'); tabssmAuto[31]=new Array('Coupé','Pick-up','Autre'); tabssmAuto[32]=new Array('D8','D8 Cosworth','S8 A','Autre'); tabssmAuto[33]=new Array('Berline','Coupé','Limousine','Autre'); tabssmAuto[34]=new Array('Tallon','Autre'); tabssmAuto[35]=new Array('JX','Autre'); tabssmAuto[36]=new Array('308','328','348','355','360','360 Modena','360 Modena Spider','400','412','456','512','BB 512','F40','F50','F550','Mondial','Testarossa','Autre'); tabssmAuto[37]=new Array('126','127','127 Fiorino','131','Argenta','Barchetta','Brava','Bravo','Croma','Cinquecento','Coupé','Doblo','Ducato','Fiorino','Marea','Multipla','Palio','Panda','Punto','Regata','Ritmo','Scudo','Seicento','Stilo','Strada','Tempra','Tipo','Ulysse','Uno','X1/9','X1/9 Bertone','Autre'); tabssmAuto[38]=new Array('Aérostar','Bronco','Capri','Compact','Contour','Cougar','Courrier','Escort','Explorer','Fiesta','Focus','Galaxy','Granada','Ka','Maverick','Mondeo','Mustang','Orion','Probe','Puma','Scorpio','Sierra','Taunus','Tourneo','Transit','Autre'); tabssmAuto[39]=new Array('Atou','Caro','Autre'); tabssmAuto[40]=new Array('G33','Autre'); tabssmAuto[41]=new Array('Mini Fourgon','Mini Bus','Rascal','Autre'); tabssmAuto[42]=new Array('Dallas','Torpedo','Autre'); tabssmAuto[43]=new Array('Berlinette','Autre'); tabssmAuto[44]=new Array('Accord','Civic','Concerto','CR-V','CRX','EV','HR-V','Integra','Jazz','Legend','Logo','NSX','Prelude','S2000','Shuttle','Stream','Autre'); tabssmAuto[45]=new Array('Hummer','Autre'); tabssmAuto[46]=new Array('Accent','Atos','Atos Prime','Coupé','Elantra','Galloper','H1','H100','Lantra','Pony','Santa Fe','Satellite','Sonata','SCoupe','Trajet','XG','Autre'); tabssmAuto[47]=new Array('Lato','Autre'); tabssmAuto[48]=new Array('3 Cylindres','500','650','990','Mini','Mini Commerciale','Autre'); tabssmAuto[49]=new Array('Berline','Coupé','Wagonette','Autre'); tabssmAuto[50]=new Array('Iso','Autre'); tabssmAuto[51]=new Array('Isuzu','Trooper','Autre'); tabssmAuto[52]=new Array('Daily','Iveco','Turbo Daily','Autre'); tabssmAuto[53]=new Array('Daimler','MK','Sovereign','S-Type','Type E','XJ6','XJ8','XJR','XJR-S','XJS','XK','XK8','XKR','X-TYPE','XJ','XJ12','Autre'); tabssmAuto[54]=new Array('Cherokee','CJ7','Grand Cherokee','Wrangler','Autre'); tabssmAuto[55]=new Array('Besta','Besta Fourgon','Carens','Carnival','K2700','Magentis','Pregio','Pride','Rio','Rocsta','Sephia','Shuma','Sportage','Autre'); tabssmAuto[56]=new Array('110','111','112','1200','2104','2105','2107','Kalinka','Natacha','Niva','Samara','Autre'); tabssmAuto[57]=new Array('350','3500','400','Countach','Coupé','Diablo','Espada','Jalpa','Jarama','Islero','LM','Miura','Muciélago','Sagona','Silhouette','Urraco','Autre'); tabssmAuto[58]=new Array('Beta','Coupé','Dedra','Delta','Gamma','Intégrale','Kappa','Lybra','Prisma','Thema','Trevi','Y','Y10','Zeta','Autre'); tabssmAuto[59]=new Array('90','Defender','Discovery','Freelander','Moke','Range Rover','Autre'); tabssmAuto[60]=new Array('GS 300','GS 430','IS 200','IS 300','LS 400','LS 430','RX 300','SC 430','Autre'); tabssmAuto[61]=new Array('Camionetto','Autre'); tabssmAuto[62]=new Array('Aviator','Blackwood','Continental','LS','Navigator','Town Car','Autre'); tabssmAuto[63]=new Array('Eclat','Elan','Elise','Esprit','Excel','Exige','Autre'); tabssmAuto[64]=new Array('CJ','Autre'); tabssmAuto[65]=new Array('800','Autre'); tabssmAuto[66]=new Array('222','228','3200','3200 GT','422','430','Biturbo','Ghibli','Karif','Quattroporte','Shamal','Spider 2000','Spider 2800','Autre'); tabssmAuto[67]=new Array('Bagheera','Murena','Rancho','Autre'); tabssmAuto[68]=new Array('121','323','6','626','929','B2000','B2500','Bongo','Demio','E2000','E2200','MPV','MS-X','MX3','MX5','MX6','Pick-up','Premacy','RX7','SW-X','T2500','Xedos','Autre'); tabssmAuto[69]=new Array('Club','Concept','Ranch','Track','Autre'); tabssmAuto[70]=new Array('190','200','208','220','230','240','250','260','280','300','308','320','350','380','400','408','420','450','500','560','600','Cabriolet W124','CL','Classe A','Classe C','Classe E','Classe G','Classe M','Classe S','Classe V','CLK','Coupé W124','MB100','SL','SLK','Sprinter','T1','T2','Vario','Vito','Autre'); tabssmAuto[71]=new Array('MGF','ZR','ZS','ZT','Autre'); tabssmAuto[72]=new Array('Mini','Autre'); tabssmAuto[73]=new Array('3000','3000 GT','Canter','Carisma','Colt','Galant','L200','Lancer','Pajero','Pick-up L200','Space Runner','Space star','Space Wagon','Autre'); tabssmAuto[74]=new Array('4/4 Série','Plus 4','Plus 8','Autre'); tabssmAuto[75]=new Array('Coupé','Autre'); tabssmAuto[76]=new Array('Coupé','Autre'); tabssmAuto[77]=new Array('100','100 NX','120','160','180','200','200 SX','300','300 ZX','2000','Almera','Atleon','Bluebird','Break','Cabstar','Cedric','Cherry','Coupé','Eco T100','King cab','Laurel','Maxima','Micra','NX','Patrol','Pick-up','Prairie','Primera','Serena','Stanza','Sunny','Sylvia','Terrano','Tino','Trade','Urvan','Vanette','Violet','X-Trail','Autre'); tabssmAuto[78]=new Array('442','88','98','Brougham','Coupé','Cutlass','Delta','Dynamic','F85','Omega','Pick-up','Starfire','Super','Toronado','Vista Cruiser','Autre'); tabssmAuto[79]=new Array('Agila','Ascona','Astra',' Calibra','Campo','Combo','Corsa','Frontera','Kadett','Manta','Monterey','Monza','Movano','Rekord','Senator','Omega','Sintra','Speedster','Tigra','Vectra','ZAFIRA','Autre'); tabssmAuto[80]=new Array('104','106','204','205','206','206 CC','304','305','306','307','309','404','405','406','406 coupé','504','505','604','605','607','806','807','Boxer','Expert','J5','J7','J9','Partner','Autre'); tabssmAuto[81]=new Array('Porter','Autre'); tabssmAuto[82]=new Array('125','1300','1500','Coupé','Montana','Pick-up','Autre'); tabssmAuto[83]=new Array('Coupé','Firebird','Trans Sport','Transam','Autre'); tabssmAuto[84]=new Array('356','911','911 Carrerra','911 Carrerra 4','911 GT3','911 Turbo','914','924','928','930','944','964','968','993','996','Boxster','Cayenne','Autre'); tabssmAuto[85]=new Array('313','Autre'); tabssmAuto[86]=new Array('R11','R14','R16','R18','R19','R20','R21','R25','R30','R4','R5','R9','Alpine','Avantime','Clio','Espace','Estafette','Express','Fuego','Grand Espace','Kangoo','Laguna','Laguna II','Master','Megane','Megane II','Safrane','Scenic','Spider','Super 5','Trafic','Twingo','Vel Satis','Autre'); tabssmAuto[87]=new Array('Mascott','Messenger','Autre'); tabssmAuto[88]=new Array('Camargue','Corniche','Coupé','Phantom','Silver Cloud','Silver Dawn','Silver Shadow','Silver Spirit','Silver Spur','Silver Wraith','Touring','Autre'); tabssmAuto[89]=new Array('100','111','114','115','200','2000','213','214','216','218','220','2200','2300','2400','25','2600','414','416','418','420','420','45','618','620','623','800','75','820','820','825','827 Estate','MG','Autre'); tabssmAuto[90]=new Array('9-3','9-5','9.3','900','9000','Autre'); tabssmAuto[91]=new Array('Nairobi','S410','Samurai','Vitara','Autre'); tabssmAuto[92]=new Array('Alhambra','Arosa','Bolero','Cordoba','Fura','Ibiza','Inca','Leon','Malaga','Marbella','Ronda','Terra','Toledo','Autre'); tabssmAuto[93]=new Array('100','105','110','120','130','Fabia','Favorit','Felicia','Autre'); tabssmAuto[94]=new Array('Smart','Autre'); tabssmAuto[95]=new Array('Family','Korando','Musso','Autre'); tabssmAuto[96]=new Array('Forester','Impreza','Justy','Legacy','Mini Jumbo','Outback','SVX','Vanille','Autre'); tabssmAuto[97]=new Array('Alto','Baleno','Carry','Grand Vitara','Ignis','Jimny','Samurai','Super Carry','Swift','Vitara','Wagon R','X90','Autre'); tabssmAuto[98]=new Array('Horizon','Murena','Samba','Solara','Tagora','Autre'); tabssmAuto[99]=new Array('Tangara','Autre'); tabssmAuto[100]=new Array('Avensis','Camry','Carina E','Carina II','Celica','Corolla','Dyna','Fun Cruiser','HDJ 80','Hi-Ace','Hi Lux','Land Cruiser','Lite Ace','Lite Ace Fourgon','LJ70','Model F','MR','Paseo','Picnic','Previa','Prius','Rav4','Runner','Starlet','Supra','Tercel','Yaris','Yaris Verso','Autre'); tabssmAuto[101]=new Array('TR7','Autre'); tabssmAuto[102]=new Array('350I','Cerbera','Chimaera 400','Griffith 400','Griffith 500','Griffith Roadster S','S4','Autre'); tabssmAuto[103]=new Array('Alter','Autre'); tabssmAuto[104]=new Array('260','400','Atlantique','Cabriolet Transcup','Coupé Atmo','Coupé Turbo','Spider','Autre'); tabssmAuto[105]=new Array('Bora','Caddy','Caravelle','Coccinelle','Combi','Corrado','Golf','Jetta','Kombi','LT','Lupo','Multivan','New Beetle','Passat','Polo','Scirocco','Sharan','Transporter','Vento','W12','Autre'); tabssmAuto[106]=new Array('240','340','343','360','440','460','480','480 ES','740','745','760','780','850','940','960','C70','S40','S60','S70','S80','S90','V40','V70','Autre'); tabssmAuto[107]=new Array('45','55','65','Autre'); tabssmAuto[108]=new Array('1100','1300','Coupé','Yugo','Autre');


/* affichage de la liste SOUSmarque auto et moto*/

function afficheListAutossmarque(valeur){
with(document.all){
autossmarque.size=1;
marque.value=tabAuto[valeur+1];
autossmarque.outerHTML=autossmarqueOptions(tabssmAuto[valeur]);
}}

function afficheListMotossmarque(valeur){
with(document.all){
motossmarque.size=1;
marque.value=tabMoto[valeur];
motossmarque.outerHTML=motossmarqueOptions(tabssmMoto[valeur]);
}}

function autossmarqueOptions(tableau){
var chaine="";
var nb="this.selectedIndex";
chaine="<select name=autossmarque size=1 onChange=\"document.all.modele.value=";
chaine=chaine+"this.options["+nb+"].value\"";
for(i=0;i< tableau.length;i++)
chaine=chaine+"<option value="+tableau[i]+">"+tableau[i];
chaine=chaine+"</select>";
return chaine;
}

function motossmarqueOptions(tableau){
var chaine="";
var nb="this.selectedIndex";
chaine="<select name=motossmarque size=1 onChange=\"document.all.modele.value=";
chaine=chaine+"this.options["+nb+"].value\"";
for(i=0;i< tableau.length;i++)
chaine=chaine+"<option value="+tableau[i]+">"+tableau[i];
chaine=chaine+"</select>";
return chaine;
}

/* affichage de la liste marque auto et moto*/

function afficheListAuto(){
with(document.all){
automobile.size=1;
automobile.outerHTML=autoOptions(tabAuto);
}}

function afficheListMoto(valeur){
with(document.all){
moto.size=1;
moto.outerHTML=motoOptions(tabMoto);
}}

function autoOptions(tableau){
var chaine="";
var nb="this.selectedIndex";
chaine="<select name=automobile size=1 onChange=\'afficheListAutossmarque("+nb+");\'";

for(i=0;i< tableau.length;i++)
chaine=chaine+"<option value="+tableau[i]+">"+tableau[i];
chaine=chaine+"</select>";
return chaine;
}

function motoOptions(tableau){
var chaine="";
var nb="this.selectedIndex";
chaine="<select name=moto size=1 onChange=\'afficheListMotossmarque("+nb+");\'";

for(i=0;i< tableau.length;i++)
chaine=chaine+"<option value="+tableau[i]+">"+tableau[i];
chaine=chaine+"</select>";
return chaine;
}


