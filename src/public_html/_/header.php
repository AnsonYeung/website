<?php
include_once "../../php/head.php";
$slashExists = substr($uri, -1) === "/";
if (!$slashExists) $uri .= "/";
preg_match_all("/^.{9}([^?]*)/", $uri, $result);
$result = $result[1][0];
?><meta http-equiv="Content-Security-Policy" content="<?php echo $csp ?>" />
<meta http-equiv="X-UA-Compatible" content="edge" />
<meta name="googlebot" content="noarchieve" />
<title>Index of <?php echo $result ?> - YSH</title>
<base href="<?php echo $uri ?>" />
<link rel="alternative" href="<?php echo $slashExists ? substr($uri, -1, -strlen($uri) + 1) : $uri ?>" />
<style nonce="<?php echo $style_nonce ?>">
<?php include __DIR__ . "/../style/bootstrap.inline.css" ?>
#loading:after {
	content: "Loading...";
	color: #00f;
	display: block;
	font-size: 4em;
	font-weight: 700;
	text-align: center;
}

#loading table {
	display: none;
}

td {
	vertical-align: middle !important;
}

td > a {
	display: block;
	margin: -12px -8px;
	padding: 12px 8px;
}

a:link {
	text-decoration: none;
	color: #555;
}

a:visited {
	text-decoration: none;
	color: #777;
}

img {
	margin: 3px 0;
}

.description {
	font-style: italic;
	font-size: 90%;
	color: #777;
}
</style>
<script nonce="<?php echo $script_nonce ?>">
const initTable = function () {
	const tds = document.getElementsByTagName("td");
	const tdsLen = tds.length;
	for (let i = 0; i < tdsLen; i++) {
		tds[i].removeAttribute("align");
		tds[i].removeAttribute("valign");
	}
	const trs = document.getElementsByTagName("tr");
	trs[trs.length - 1].remove();
	trs[1].remove();
	document.getElementsByTagName("table")[0].classList.add("table", "table-hover");
	document.getElementById("loading").removeAttribute("id");
}
if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading"){
	initTable();
} else {
	document.addEventListener('DOMContentLoaded', initTable);
}
</script><?php include "../../php/navbar.php" ?><header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1>Directory: <?php echo $result?></h1></header>
<!-- htmlmin:ignore --><div class="row"><div class="col-12 table-responsive" id="loading"><!-- htmlmin:ignore -->
<p>File list:</p>
<noscript><div class="alert alert-danger">ERROR: File fetching require JavaScript.</div></noscript>