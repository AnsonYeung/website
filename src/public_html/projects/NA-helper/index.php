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
<script nonce="<?php echo $script_nonce;?>">
safeReq(["NA", "/~S151204/projects/NA-helper/NA?callback=define"], function (NA, assets) {
	NA.assets = assets;
});
</script>
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
