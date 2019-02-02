<?php include_once "../../php/head.php" ?>
<title>Maintenence break - YSH</title>
<meta name="googlebot" content="noindex" />
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/

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
<?php include "../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Welcome to <span itemprop="name">Yeung Sin Hang</span>'s webpage!</h1></header>
<div class="jumbotron">
	<h2>Maintenence break</h2>
	<p>Sorry, my website is currently under maintenence. Come back later.</p>
</div>
<article class="text-center" itemscope itemtype="http://schema.org/Game">
	<h2><span itemprop="name">Minecraft</span> <small>(keyboard-only)</small></h2>
	<div class="iframe-container text-center">
		<link itemprop="gameLocation" href="http://student.tanghin.edu.hk/~S151204/projects/processing.js/minecraft.html" />
		<iframe width="400" height="400" src="/~S151204/projects/processing.js/minecraft.html"></iframe>
	</div><br />
	<p>This program was created by <a href="https://www.khanacademy.org/computer-programming/minecraft/1523326697">Bennimus</a>.</p>
	<p><b>Please use <a href="https://www.google.com/chrome">Google Chrome</a> for the best experience.</b></p>
</article>
<div class="row">
	<article class="col-md-4">
		<h3>Password encrypt system upgraded!</h3>
		<p>I have upgraded the password encryption system to add more security to the website.
			However, the previous password can't be decoded because it uses a encrypt-only method.
			Please login with your password and the server will automatically upgrade your
			password encryption to the newest one.</p>
		<p><a class="btn btn-primary" href="login">Login &gt;&gt;</a></p>
		<hr class="d-md-none">
	</article>
	<article class="col-md-4">
		<h3>Chat room bug was fixed!</h3>
		<p>After a security update, the chat room was closed. Now, the chat room is back!</p>
		<p><a class="btn btn-primary" href="projects/chat-room">Chat now &gt;&gt;</a></p>
		<hr class="d-md-none">
	</article>
	<article class="col-md-4">
		<h3>Admin tools is added!</h3>
		<p>If you have a privilege level of 1 (elevated) or more, you will have access to the admin tools
			in your user profile (can be found on the top right corner of the navigation bar once you logged in)</p>
	</article>
	<div class="col-12"><hr></div>
	<article class="col-md-4">
		<h3>Chat room is now released</h3>
		<p>Finally, chat room has come! Go and try out!</p>
		<p><a class="btn btn-primary" href="projects/chat-room">Chat now &gt;&gt;</a></p>
		<hr class="d-md-none">
	</article>
	<article class="col-md-4">
		<h3>Files is now deprecated (removed).</h3>
		<p>After careful think of the meaning of files, I decided to remove "files" from my website. The files are moved to games and projects.</p>
		<hr class="d-md-none">
	</article>
	<article class="col-md-4">
		<h3>Newspaper Asignment doer has released!</h3>
		<p>Newspaper Assignment doer can make you easier with checking dictionaries.</p>
		<p><a class="btn btn-primary" href="projects/NA-helper">Try out now &gt;&gt;</a></p>
	</article>
</div>
<?php include "../../php/footer.php" ?>
