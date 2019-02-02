<?php
if (isset($_GET["url"])) {
	echo file_get_contents($_GET["url"]);
	die();
}
include "../../../php/head.php";
?>
<title>Download Booster - YSH</title>
<meta name="keywords" content="YSH, Download Booster, tanghin" />
<meta name="description" content="Download booster helps you to download faster in the tanghin internet." />
<style nonce="<?php echo $style_nonce;?>">
<?php include "../../style/bootstrap.inline.css" ?>
</style>
<script nonce="<?php echo $script_nonce;?>">
safeReq(["download"], function () {

});
</script>
<?php include "../../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Download booster</h1></header>
<section>
	Currently in development. This tool <strong>MAY</strong> help with the slow downloading problems in school.
</section>
URL: <input id="url" />
<button class="btn btn-light" id="download">Download</button>
<?php include "../../../php/footer.php" ?>
