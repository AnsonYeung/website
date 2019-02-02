<?php include_once __DIR__ . "/../../php/head.php" ?>
<title>401 Authorization Required - YSH</title>
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/
</style>
<?php include __DIR__ . "/../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 mb-3 mt-5 pb-2 hscroll"><h1 class="text-danger">401 Authorization Required</h1></header>
<article class="jumbotron">
	<h2 class="text-center text-danger">Sorry, but we can't let you to access that file</h2>
	<section>
		<h2>401. That's an error.</h2>
		<p>This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn"t understand how to supply the credentials required.</p>
		<h2>Details</h2>
		<p>This server could not verify that you have access to <code><?php echo $localuri ?></code>, thus this message is shown.</p>
	</section>
</article>
<?php include __DIR__ . "/../../php/footer.php" ?>
