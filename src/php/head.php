<?php

require_once "main.php";
$school = (isset($_COOKIE["school"]) and $_COOKIE["school"] === "true");
$uri = strtok($_SERVER["REQUEST_URI"], "?");
$localuri = substr($uri, 9);
$script_nonce = base64_encode(openssl_random_pseudo_bytes(16));
$style_nonce = base64_encode(openssl_random_pseudo_bytes(16));
$csp = str_replace("\n", " ", <<<HEADER
base-uri 'self';
connect-src ws://student.tanghin.edu.hk/~S151204/
http://student.tanghin.edu.hk/~S151204/
http://moondanz.tanghin.edu.hk/~S151204/;
default-src 'self' https://*.google.com;
font-src 'self'
https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/fonts/
https://fonts.gstatic.com
data:;
frame-src 'self' https://apis.google.com https://accounts.google.com;
form-action 'self';
img-src 'self' data: https://www.google-analytics.com https://stats.g.doubleclick.net https://www.google.com https://www.google.com.hk;
script-src
student.tanghin.edu.hk/~S151204/scripts/
student.tanghin.edu.hk/~S151204/projects/NA-helper/NA
https://cdnjs.cloudflare.com/ajax/libs/
https://maxcdn.bootstrapcdn.com/
https://ajax.googleapis.com/ajax/libs/
https://www.google-analytics.com/analytics.js
https://apis.google.com/js/platform.js
https://apis.google.com/_/scs/apps-static/_/js/
https://www.gstatic.com/firebasejs/4.12.1/firebase.js
https://unpkg.com/
'unsafe-inline' 'nonce-$script_nonce';
style-src
'unsafe-inline'
'self'
https://maxcdn.bootstrapcdn.com
https://fonts.googleapis.com
'nonce-$style_nonce'
;
object-src 'none'
HEADER
);
$db = new Database("accounts");
$accounts = $db->read();
require "session.php";
session_commit();
if (isset($username)) {
    $profile = $accounts[strtoupper($username)];
}
header("X-UA-Compatible: IE=edge");
header("Content-Security-Policy: " . $csp . "; report-uri /~S151204/csp");

?><!DOCTYPE html>
<!-- htmlmin:ignore --><html lang="en" itemscope itemtype="http://schema.org/WebPage"><head><!-- htmlmin:ignore -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,initial-scale=1.0" />
<meta name="author" content="Anson Yeung" />
<meta name="theme-color" content="DarkBlue" />
<meta name="msapplication-config" content="/~S151204/browserconfig.xml?v=4" />
<meta itemprop="name" content="YSH" />
<link itemprop="url" href="http://student.tanghin.edu.hk/~S151204/" />
<link rel="preconnect" href="https://ajax.googleapis.com" />
<link rel="preconnect" href="https://apis.google.com" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://www.google-analytics.com" />
<link rel="prefetch" href="https://apis.google.com/js/platform.js" />
<link rel="preload" as="script" href="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" crossorigin />
<link rel="preload" as="script" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" crossorigin />
<link rel="preload" as="font" type="font/woff" href="http://student.tanghin.edu.hk/~S151204/fonts/open-iconic.woff" crossorigin />
<link rel="apple-touch-icon" sizes="180x180" href="/~S151204/images/apple-touch-icon.png?v=4" />
<link rel="icon" type="image/png" sizes="32x32" href="/~S151204/images/favicon-32x32.png?v=4" />
<link rel="icon" type="image/png" sizes="16x16" href="/~S151204/images/favicon-16x16.png?v=4" />
<link rel="manifest" href="/~S151204/manifest.json?v=4" />
<link rel="mask-icon" href="/~S151204/images/safari-pinned-tab.svg?v=4" color="#808080" />
<link rel="shortcut icon" href="/~S151204/images/favicon.ico?v=4" />
<!-- htmlmin:ignore --><!--[if lt IE 9]><!-- htmlmin:ignore -->
<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<!-- htmlmin:ignore --><![endif]--><!-- htmlmin:ignore -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" async defer id="popper"></script>
<script src="/~S151204/scripts/main.js" type="module" async></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.2.5/polyfill.min.js" async defer></script>
<script src="https://maxcdn.bootstrapcdn.com/js/ie10-viewport-bug-workaround.js" async defer></script>
<script src="https://www.gstatic.com/firebasejs/4.12.1/firebase.js" async defer id="firebase"></script>
<script src="https://www.google-analytics.com/analytics.js" async defer id="ga"></script>
<script nonce="<?php echo $script_nonce ?>">
const firebaseEl = document.getElementById("firebase");
const gaEl = document.getElementById("ga");
firebaseEl.addEventListener("load", function () {
	firebase.initializeApp({
		apiKey: "AIzaSyBOrZGxBDRMjJAyEwGQp96rHprSkPs4vKY",
		authDomain: "ysh-homepage.firebaseapp.com",
		databaseURL: "https://ysh-homepage.firebaseio.com",
		projectId: "ysh-homepage",
		storageBucket: "ysh-homepage.appspot.com",
		messagingSenderId: "1093588347904"
	});
});
gaEl.addEventListener("load", function () {
	// s151204@tanghin.edu.hk: UA-98324003-2
	// yeungsinhangsmall@gmail.com is used instead now.
	ga("create", "UA-105152581-1", "auto");
	ga("send", "pageview");
});
</script>