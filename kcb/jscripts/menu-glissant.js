function regenerate(){
window.location.reload()
}
function regenerate2(){
if (document.layers)
setTimeout("window.onresize=regenerate",400)
}
window.onload=regenerate2
if (document.all){
document.write('</div>')
themenu=document.all.slidemenubar2.style
rightboundary=0
leftboundary=-150
}
else{
themenu=document.layers.slidemenubar
rightboundary=150
leftboundary=10
}
function pull(){
if (window.drawit)
clearInterval(drawit)
pullit=setInterval("pullengine()",50)
}
function draw(){
clearInterval(pullit)
drawit=setInterval("drawengine()",50)
}
function pullengine(){
if (document.all&&themenu.pixelLeft<rightboundary)
themenu.pixelLeft+=5
else if(document.layers&&themenu.left<rightboundary)
themenu.left+=5
else if (window.pullit)
clearInterval(pullit)
}
function drawengine(){
if (document.all&&themenu.pixelLeft>leftboundary)
themenu.pixelLeft-=5
else if(document.layers&&themenu.left>leftboundary)
themenu.left-=5
else if (window.drawit)
clearInterval(drawit)
}

