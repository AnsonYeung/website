<?php include_once __DIR__ . "/../../php/head.php" ?>
<title>400 Bad Request - YSH</title>
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/
</style>
<?php include __DIR__ . "/../../php/navbar.php";?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1 class="text-danger">400 Bad Request</h1></header>
<article class="jumbotron">
	<h2 class="text-center text-danger">Sorry, but we can't understand your request.</h2>
	<section>
		<h2>400. That's an error.</h2>
		<p>This server could not understand the url you've sent to the server.</p>
		<h2>Details</h2>
		<p>You have sent <code><?php echo $localuri ?></code> to this server which it can"t understand.</p>
	</section>
</article>
<?php include __DIR__ . "/../../php/footer.php" ?>
