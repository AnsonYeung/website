<?php include "../../../php/head.php" ?>
<title>Reversi - YSH</title>
<meta name="keywords" content="YSH, Yeung Sin Hang, games, reversi" />
<meta name="description" content="Reversi written in React" />
<style nonce="<?php echo $style_nonce?>"><?php include "../../style/bootstrap.inline.css"; include "../../style/reversi.css" //?></style>
<script nonce="<?php echo $script_nonce?>">
safeReq([
	"react",
	"react-dom",
	"reversi"
]);
</script>
<?php include "../../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1>Reversi</h1></header>
<div id="base"></div>
<?php include "../../../php/footer.php" ?>