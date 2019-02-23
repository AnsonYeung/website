<?php include "../../../php/head.php" ?>
<title>Reversi - YSH</title>
<meta name="keywords" content="YSH, Yeung Sin Hang, games, reversi" />
<meta name="description" content="Reversi written in React" />
<script src="https://unpkg.com/react@16/umd/react.production.min.js" async defer id="react"></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js" async defer id="react-dom"></script>
<script src="https://unpkg.com/prop-types@15/prop-types.min.js" async defer id="prop-types"></script>
<script src="/~S151204/scripts/reversi.js" type="module" async></script>
<script nonce="<?php echo $script_nonce ?>">
window.ready = new Promise(resolve => {
	let cnt = 0;
	let oneReady = function () {
		if (++cnt === 3) {
			cnt = null;
			document.getElementById("react").removeEventListener("load", oneReady);
			document.getElementById("react-dom").removeEventListener("load", oneReady);
			document.getElementById("prop-types").removeEventListener("load", oneReady);
			oneReady = null;
			resolve();
		}
	};
	document.getElementById("react").addEventListener("load", oneReady);
	document.getElementById("react-dom").addEventListener("load", oneReady);
	document.getElementById("prop-types").addEventListener("load", oneReady);
});
</script>
<style nonce="<?php echo $style_nonce?>">
<?php include "../../style/bootstrap.inline.css" ?>/**/
</style>
<?php include "../../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1>Reversi</h1></header>
<div id="base"></div>
<?php include "../../../php/footer.php" ?>