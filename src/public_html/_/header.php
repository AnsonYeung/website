<?php
include_once "../../php/head.php";
$slashExists = substr($uri, -1) === "/";
if (!$slashExists) $uri .= "/";
preg_match_all("/^.{9}([^?]*)/", $uri, $result);
$result = $result[1][0];
?>
<!-- htmlmin:ignore -->
<meta http-equiv="Content-Security-Policy" content="<?php echo $csp ?>" />
<meta http-equiv="X-UA-Compatible" content="edge" />
<meta name="googlebot" content="noarchieve" />
<title>Index of <?php echo $result ?> - YSH</title>
<base href="<?php echo $uri ?>" />
<link rel="alternative" href="<?php echo $slashExists ? substr($uri, -1, -strlen($uri) + 1) : $uri ?>" />
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/
.loading:after {
	content: "Loading...";
	color: #00f;
	display: block;
	font-size: 4em;
	font-weight: 700;
	text-align: center;
}

.loading table {
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
safeReq(["jquery"], function ($) {
	$("td").removeAttr("align").removeAttr("valign");
	$("tr:eq(1),tr:eq(-1)").remove();
	$("table").addClass("table table-hover");
	$(".loading").removeClass("loading");
});
</script>
<?php include "../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1>Directory: <?php echo $result?></h1></header>
<div class="row">
<div class="col-12 table-responsive loading">
<p>File list:</p>
<noscript><div class="alert alert-danger">ERROR: File fetching require JavaScript.</div></noscript>
<!-- htmlmin:ignore -->