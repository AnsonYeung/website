<?php include_once __DIR__ . "/../../php/head.php" ?>
<title>404 Not Found - YSH</title>
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/
</style>
<?php include __DIR__ . "/../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1 class="text-danger">404 Not Found</h1></header>
<article class="jumbotron">
	<h2 class="text-center text-danger">Sorry, but we can't find the file that you've requested.</h2>
	<section>
		<h2>404. That's an error.</h2>
		<p>404 is basically a code representing that the URL you've requested is not found on the server.</p>
		<h2>Details</h2>
		<p>You have requested the file <code><?php echo $localuri ?></code> from this server which does not exist.</p>
	</section>
</article>
<?php include __DIR__ . "/../../php/footer.php" ?>
