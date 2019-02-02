<?php include_once __DIR__ . "/../../php/head.php" ?>
<title>410 Gone - YSH</title>
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/
</style>
<?php include __DIR__ . "/../../php/navbar.php";?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1 class="text-danger">410 Gone</h1></header>
<article class="jumbotron">
	<h2 class="text-center text-danger">Sorry, but it looks like that file was removed.</h2>
	<section>
		<h2>410. That's an error.</h2>
		<p>The resources requested has been removed.</p>
		<h2>Details</h2>
		<p>The requested resource <code><?php echo $localuri ?></code> is no longer available on this server and there is no forwarding address. Please remove all references to this resource.</p>
	</section>
</article>
<?php include __DIR__ . "/../../php/footer.php" ?>
