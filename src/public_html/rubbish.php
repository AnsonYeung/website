<?php
$script_nonce = base64_encode(openssl_random_pseudo_bytes(16));
$style_nonce = base64_encode(openssl_random_pseudo_bytes(16));
$csp_for_css = "";
if (!isset($no_csp_for_css)) {
	$csp_for_css = "'nonce-".$style_nonce."'";
}
header(str_replace("\n", " ", <<<HEADER
Content-Security-Policy:
base-uri 'self';
font-src 'self'
maxcdn.bootstrapcdn.com/bootstrap/3.3.7/fonts/
fonts.gstatic.com;
frame-src 'self' apis.google.com accounts.google.com;
form-action 'self';
default-src 'self';
img-src 'self' www.google-analytics.com stats.g.doubleclick.net www.google.com www.google.com.hk;
script-src
student.tanghin.edu.hk/~S151204/scripts/
maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/boostrap.min.js
maxcdn.bootstrapcdn.com/js/ie10-viewport-bug-workaround.js
ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js
www.google-analytics.com/analytics.js
'unsafe-inline' 'nonce-$script_nonce' 'strict-dynamic';
style-src 'unsafe-inline' 'self' maxcdn.bootstrapcdn.com fonts.googleapis.com $csp_for_css;
object-src 'none';
report-uri /~S151204/data/csp
HEADER
));
header("X-UA-Compatible: IE=edge");
session_set_cookie_params(1800, "/~S151204");session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
	session_unset();
	session_destroy();
}
$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['CREATED'])) {
	$_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
	session_regenerate_id(true);
	$_SESSION['CREATED'] = time();
}
if (isset($_SESSION["username"])) {
	$accounts = json_decode(file_get_contents("../data/accounts.json"), true);
	if (!isset($accounts[strtoupper($_SESSION["username"])])) {
		session_unset();
		session_destroy();
	}
}
session_write_close();
$school = (isset($_COOKIE["school"]) and $_COOKIE["school"] === "true");
$uri = $_SERVER["REQUEST_URI"];

?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,initial-scale=1.0" />
<meta name="author" content="Anson Yeung" />
<meta name="theme-color" content="DarkBlue" />
<meta name="msapplication-config" content="/~S151204/browserconfig.xml?v=4" />
<noscript><meta http-equiv="Refresh" content="10; /~S151204/" /></noscript>
<meta itemprop="name" content="YSH" />
<link itemprop="url" href="http://student.tanghin.edu.hk/~S151204/" />
<link rel="preconnect" href="//ajax.googleapis.com">
<link rel="apple-touch-icon" sizes="180x180" href="/~S151204/images/apple-touch-icon.png?v=4" />
<link rel="icon" type="image/png" sizes="32x32" href="/~S151204/images/favicon-32x32.png?v=4" />
<link rel="icon" type="image/png" sizes="16x16" href="/~S151204/images/favicon-16x16.png?v=4" />
<link rel="manifest" href="/~S151204/manifest.json?v=4" />
<link rel="mask-icon" href="/~S151204/images/safari-pinned-tab.svg?v=4" color="#808080" />
<link rel="shortcut icon" href="/~S151204/images/favicon.ico?v=4" />
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<title>Rubbish - YSH</title>
<style nonce="<?php echo $style_nonce;?>">
@-ms-viewport {width: device-width}

@-o-viewport  {width: device-width}

@viewport     {width: device-width}

* {
	box-sizing: border-box;
}

iframe {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	border: 2px solid red;
}

img {
	position: fixed;
	bottom: -250px;
	left: 50%;
	transform: translateX(-50%);
}

.trigger iframe {
	animation: badsite 20s linear 5s;
}

.trigger img {
	animation: recycle-bin 20s linear 5s;
}

.trigger2 iframe {
	animation: badsite 20s linear;
}

.trigger2 img {
	animation: recycle-bin 20s linear;
}

.js {
	display: none;
}

input {
	width: 50%;
	min-width: 100px;
}

div:not(.js) + div iframe {
	top: 50px;
	bottom: 0;
}

output {
	color: red;
	font-weight: bold;
}

@keyframes badsite {
	10% {
		top: 0;
		left: 0;
		transform: rotate(0deg) scale(0.5);
	}
	40% {
		top: 0;
		left: 0;
		transform: rotate(1800deg) scale(0.4);
	}
	55% {
		top: 60%;
		left: 0;
		transform: rotate(3600deg) scale(0.01);
	}
	to {
		top: 60%;
		left: 0;
		transform: rotate(3600deg) scale(0.01);
	}
}

@keyframes recycle-bin {
	10% {
		bottom: -250px;
	}
	40% {
		bottom: 0;
	}
	55% {
		bottom: -250px;
	}
}
</style>

</head>
<body>
	<div class="js">
		<label for="src">iFrame src: </label><input id="src" value="http://student.tanghin.edu.hk/~S161516/"/>
		<button id="start">Start Animation</button>
		<output>Note: only student.tanghin.edu.hk is allowed for the iframe for security reasons.</output>
	</div>
	<div id="trigger" class="trigger">
		<img src="images/recycle-bin.png" />
		<iframe id="iframe" src="http://student.tanghin.edu.hk/~S161516/" sandbox="allow-scripts allow-same-origin"></iframe>
	</div>
	<script nonce="<?php echo $script_nonce;?>">
	var src = document.getElementById("src");
	var iframe = document.getElementById("iframe");
	var out = document.getElementsByTagName("output")[0];
	document.getElementById("start").addEventListener("click", function (e) {
		document.getElementById("trigger").classList.add("trigger2");
		this.parentElement.remove();
		setTimeout(function () {
			location = "/~S151204";
		}, 10000);
	});
	src.addEventListener("change", (function () {
		var lastVal = "http://student.tanghin.edu.hk/~S161516/";
		return function (e) {
			if ((val = this.value).indexOf("http://student.tanghin.edu.hk/~S151204") === 0) {
				this.value = lastVal;
				if (iframe.contentWindow.location.href !== lastVal) {
					iframe.src = lastVal;
				}
				out.innerText = "S151204's website can't be put into the recycle bin.";
				setTimeout(function () {
					out.innerText = "";
				}, 2000);
			} else if (document.getElementById("iframe").src !== val) {
				iframe.src = val;
			} else if (lastVal !== val) {
				lastVal = val;
			}
		}
	})());
	iframe.addEventListener("load", function (e) {
		src.value = this.contentWindow.location;
		src.dispatchEvent(new Event("change"));
	});
	document.getElementsByClassName("trigger")[0].classList.remove("trigger");
	document.getElementsByClassName("js")[0].classList.remove("js");
	</script>
</body>
</html>
