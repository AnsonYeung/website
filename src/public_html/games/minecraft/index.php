<?php include "../../../php/head.php" ?>
<title>Minecraft - YSH</title>
<meta name="keywords" content="YSH, Yeung Sin Hang, games, minecraft, processing.js, javascript" />
<meta name="description" content="The program is written by Bennimus on Khan Academy." />
<style nonce="<?php echo $style_nonce?>">
<?php include "../../style/bootstrap.inline.css" ?>

iframe {
	box-sizing: initial;
}

@media (max-width: 450px) {
	.iframe-container {
		margin-left: -15px;
		margin-right: -15px;
		overflow-x: auto;
	}
	iframe {
		border: 0;
	}
}

</style>
<script nonce="<?php echo $script_nonce ?>">
const loadMc = function () {
	requestAnimationFrame(function () {
		const mc = document.getElementById("minecraft");
		mc.src = "../projects/processing.js/minecraft.html";
		mc.addEventListener("load", function once () {
			document.getElementById("minecraft").classList.remove("d-none");
			mc.removeEventListener("load", once);
		});
	});	
};
if (document.readyState === "complete") {
	loadMc();
} else {
	window.addEventListener("load", loadMc);
}
</script>
<?php include "../../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1>Minecraft</h1></header>
<div class="row text-center">
	<section class="col-12">
		<h2>Game <small>(keyboard-only)</small></h2>
		<div class="iframe-container">
			<iframe width="400" height="400" id="minecraft" class="d-none"></iframe>
		</div><br />
		<p>This program was created by <a href="https://www.khanacademy.org/computer-programming/minecraft/1523326697">Bennimus</a>.</p>
		<p><strong>Please use Google Chrome for the best experience.</strong></p>
	</section>
</div>
<?php include "../../../php/footer.php" ?>
