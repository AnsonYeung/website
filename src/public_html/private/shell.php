<?php
$home = "/home/student/y2015/S151204";
if (isset($_POST["command"])) {
	if (trim($_POST["command"]) === "") {
		die();
	}
	putenv("HOME=".$home);
	putenv("PATH=".getenv("PATH").":~/bin");
	putenv("COMPOSER_HOME=".$home."/.composer");
	header("Content-Type: application/octet-stream");
	passthru($_POST["command"]." 2>/home/student/y2015/S151204/public_html/private/777/tmp.txt", $retvar);
	if ($retvar !== 0) {
		fpassthru(fopen("/home/student/y2015/S151204/public_html/private/777/tmp.txt", "r"));
		if ($retvar !== 127) {
			echo "The command ends with code ", $retvar, " which usually means failure.", PHP_EOL;
		}
	}
	die();
}
include_once "/home/student/y2015/S151204/php/head.php";
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu+Mono" />
<title>Command line - S151204"s workshop</title>
<meta name="keywords" content="S151204's workshop, WebSocket, demo" />
<meta name="description" content="WebSocket demo" />
<meta name="author" content="Anson Yeung" />
<style nonce="<?php echo $style_nonce ?>">
<?php include "/home/student/y2015/S151204/public_html/style/bootstrap.inline.css" ?>

.backspace {margin-right:-1.25vw}

b {color:white}

#log {white-space:pre-wrap}

#command-container {overflow:hidden}

#prefix {float:left}

#command {border:none;outline:none;background-color:black;color:rgba(255,255,255,0.5);width:100%}

div.container-fluid {background-color:black;color:rgba(255, 255, 255, 0.5);font-size:2.43vw;font-family:"Ubuntu Mono",monospace}
</style>
<script nonce="<?php echo $script_nonce ?>">safeReq(["shell"]);</script>
<?php include "/home/student/y2015/S151204/php/navbar.php" ?>
<header class="page-header"><h1>Shell<small> - Make sure you know what you are doing before using commands</small></h1></header>
<div class="jumbotron">
	<h2>Server command line</h2>
	<p>Some information:
	<ul><li>The server is running Linux.</li>
	<li>Commands are sent separately and so commands like cd won't work.</li>
	<li>Commands are executed using the current directory, so please don"t do something really bad or this directory will be banned from using commands.</li></ul></p>
</div>
</div>
<div class="container-fluid">
	<div id="log"></div>
	<div id="prefix">apache@moondanz.tanghin.edu.hk:<?php echo substr(__DIR__, 0, 27) === $home ? "~".substr(__DIR__, 27) : __DIR__ ?>$&nbsp;</div>
	<div id="command-container"><input id="command"></input></div>
</div>
<div class="container">
<?php include "/home/student/y2015/S151204/php/footer.php" ?>
