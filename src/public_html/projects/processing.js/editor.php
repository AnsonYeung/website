<!DOCTYPE html><html><head>
<title>Live Processing.js Editor</title>
<script type="text/javascript">
var oPrefix = '<title>JavaScript<\/title><script src="http://student.tanghin.edu.hk/~S151204/scripts/processing.min.js"><\/script><style>body{margin:0;display:flex;align-items:center;justify-content:center}:focus{outline:0px;}<\/style><canvas id="canvas"><\/canvas><script>new Processing(document.getElementById("canvas"),function(c){var m=Math,r=c.radians,g=c.degrees;c.sin=function(d){return m.sin(r(d))};c.cos=function(d){return m.cos(r(d))};c.tan=function(d){return m.tan(r(d))};c.asin=function(v){return g(m.asin(v))};c.acos=function(v){return g(m.acos(v))};c.atan=function(v){return g(m.atan(v))};c.atan2=function(x,y){return g(m.atan2(x,y))};with(c){size(400,400),background(255);var getImage=function(a){return c.loadImage("https://www.kasandbox.org/programming-images/"+a+".png")},$r=rotate,keyIsPressed,mouseIsPressed;rotate=function(a){$r(radians(a))};keyPressed=function(){keyIsPressed=!0};keyReleased=function(){keyIsPressed=!1};mousePressed=function(){mouseIsPressed=!0};mouseReleased=function(){mouseIsPressed=!1};';
var prefix = oPrefix + 'try{';
var oSuffix = '}"undefined"!=typeof draw&&(processing.draw=draw)});<\/script>';
var suffix = '\nconsole.clear()}catch(e){console&&console.log(e)}' + oSuffix;
var editboxHTML = 
'<html class="expand close">' +
'<head>' +
'<style type="text/css">' +
'.expand { width: 100%; height: 100%; }' +
'.close { border: none; margin: 0px; padding: 0px; }' +
'html,body { overflow: hidden; }' +
'<\/style>' +
'<\/head>' +
'<body class="expand close" onload="document.f.ta.focus(); document.f.ta.select();">' +
'<form class="expand close" name="f">' +
'<textarea class="expand close" style="background: #def;" name="ta" wrap="hard" spellcheck="false">' +
'<\/textarea>' +
'<\/form>' +
'<form style="position:absolute;margin:.3em;bottom:0;right:0" name="e"><label>Filename: <\/label><input name="fn" \/><button id="s">Export (Require <a id="x" href="http://caniuse.com/#feat=datauri" target="_blank">Data URI<\/a>)<\/button><\/form>' +
'<\/body>' +
'<\/html>';

var defaultStuff = '// Processing.js live editor\n';

var extraStuff = '<div style="position:absolute; margin:.3em; bottom:0em; right:0em;"><small> Created by <a href="http://www.squarefree.com/" target="_top">Jesse Ruderman<\/a> and Processing.js plugin created by <a href="../.." target="_top">Anson Yeung</a>.<\/small><\/div>';

var old = '';

var ed,ta,d;

function init()
{
	ed = editbox.document;
	d = dynamicframe.document; 
	ed.write(editboxHTML);
	ed.close();
	ta = ed.f.ta;
	ta.value = defaultStuff;
	ed.getElementById("s").addEventListener("click", download);
	ed.getElementById("x").addEventListener("click", (e) => {
		e.stopPropagation();
	});
	update();
}

function update()
{
	if (old != ta.value) {
		old = ta.value;
		d.open();
		d.write(prefix);
		d.write(old);
		d.write(suffix);
		if (old.replace(/[\r\n]/g,'') == defaultStuff.replace(/[\r\n]/g,''))
			d.write(extraStuff);
		d.close();
	}

	window.setTimeout(update, 150);
}

function download(event) {
	event.preventDefault();
	try {
		at = document.createElement("a");
		at.href = "data:text/html;charset=utf-8," + oPrefix+old+oSuffix;
		at.download = ed.e.fn.value;
		document.body.appendChild(at);
		at.click();
		at.remove();
	} catch (e) {
		event.preventDefault();
		alert(e);
	}
}

</script>
</head>

<frameset cols="*,*" onload="init();">
	<frame name="editbox" src="javascript:'';">
	<frame name="dynamicframe" src="javascript:'';">
</frameset>
</html>