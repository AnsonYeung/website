<?php include_once "../../../php/head.php" ?>
<title>NA helper - YSH</title>
<meta name="keywords" content="YSH, Newspaper Assignment helper" />
<meta name="description" content="Newspaper Assignment helper makes your life easier with checking dictionaries while doing NA." />
<style nonce="<?php echo $style_nonce;?>">
<?php include "../../style/bootstrap.inline.css" ?>

#wait, #warning, #output {
	display: none;
}

#warning {
	font-size: 3em;
}

#word-list {
	resize: vertical;
}
</style>
<script src="/~S151204/scripts/NA.js" type="module" async id="NA"></script>
<script nonce="<?php echo $script_nonce;?>">
function loadAssets(assets) {
	if (typeof NA !== "undefined") {
		NA.assets = assets;
	} else {
		document.getElementById("NA").addEventListener("load", function onNALoad() {
			NA.assets = assets;
			document.getElementById("NA").removeEventListener("load", onNALoad);
		});
	}
}
</script>
<script src="NA-helper/NA?callback=loadAssets" async defer></script>
<?php include "../../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Newspaper Assignment Helper</h1></header>
<section id="input">
	<div class="form-group">
		<label for="word-list">Enter the words below and separate them with a new line:</label>
		<textarea class="form-control" id="word-list" rows="10"></textarea>
	</div>
	<button class="btn btn-default" id="work">Submit</button>
</section>
<section id="wait" class="text-center">
	<h1>Working on it...</h1>
</section>
<section id="warning" class="warning"></section>
<section id="output"></section>
<?php include "../../../php/footer.php" ?>
