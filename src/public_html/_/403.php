<?php include_once __DIR__ . "/../../php/head.php" ?>
<title>403 Forbidden - YSH</title>
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/
</style>
<?php include __DIR__ . "/../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1 class="text-danger">403 Forbidden</h1></header>
<article class="jumbotron">
	<h2 class="text-center text-danger">Sorry, but you can't access that file.</h2>
	<section>
		<h2>403. That's an error.</h2>
		<p>403 is basically a code representing the file you've requested can't be read.</p>
		<h2>Details</h2>
		<p>The file <code><?php echo $localuri ?></code> is locked on this server.</p>
	</section>
</article>
<?php include __DIR__ . "/../../php/footer.php" ?>
