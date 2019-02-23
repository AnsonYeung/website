<?php require_once "head.php" ?><!-- htmlmin:ignore --></head><body><!-- htmlmin:ignore -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
<div class="container">
	<a class="navbar-brand" href="/~S151204/">Yeung Sin Hang<span class="d-none d-sm-inline">'s homepage</span></a>
	<button class="navbar-toggler" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="nav-content" aria-haspopup="true" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse justify-content-between" id="nav-content">
		<div class="navbar-nav">
			<div class="nav-item dropdown<?php if (substr($uri, 9, 6) === "/games") echo " active" ?>">
				<a class="nav-link dropdown-toggle" href="#" id="games" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Games</a>
				<div class="dropdown-menu" role="menu" aria-labelledby="games">
                    <a class="dropdown-item dropdown-header" href="/~S151204/games" role="menuitem">Introduction</a>
                    <a class="dropdown-item" href="/~S151204/games/minecraft" role="menuitem">Minecraft</a>
                    <a class="dropdown-item" href="/~S151204/games/nibbles" role="menuitem">Nibbles (created by teacher)</a>
                    <a class="dropdown-item" href="/~S151204/games/platform-game" role="menuitem">Platform game</a>
				</div>
			</div>
			<div class="nav-item dropdown<?php if (substr($uri, 9, 9) === "/projects") echo " active" ?>">
				<a class="nav-link dropdown-toggle" href="#" id="projects" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Projects</a>
				<div class="dropdown-menu" role="menu" aria-labelledby="projects">
					<a class="dropdown-item dropdown-header" href="/~S151204/projects" role="menuitem">Introduction</a>
					<a class="dropdown-item" href="/~S151204/projects/browser" role="menuitem">Browser</a>
                    <a class="dropdown-item" href="/~S151204/projects/chat-room" role="menuitem">Chat Room</a>
                    <a class="dropdown-item" href="/~S151204/projects/NA-helper" role="menuitem">NA Helper</a>
                    <a class="dropdown-item" href="/~S151204/projects/platform-game" role="menuitem">Platform Game</a>
                    <a class="dropdown-item" href="/~S151204/projects/processing.js" role="menuitem">Mini JS programs</a>
				</div>
			</div>
			<a class="nav-item nav-link" href="mailto:s151204@TANGHIN.EDU.HK?Subject=Webpage%20Feedback">Feedback</a>
		</div>
		<div class="navbar-nav">
<?php if ($school):?>
			<a class="nav-link active" href="#" id="school">SCHOOL MODE</a>
<?php endif;?>
			<a class="nav-link<?php if (substr($uri, 9, 8) === "/private") echo " active" ?>" href="/~S151204/private">Admin area</a>
<?php if (isset($username)): ?>
			<a class="nav-link <?php if ($localuri === "/_/user" && isset($_GET["username"]) && $_GET["username"] === $username) echo " active" ?>" href="/~S151204/profile/<?php echo $username ?>"><span class="oi oi-people"></span> <?php echo $username ?></a>
            <a class="nav-link" href="/~S151204/user/logout" id="logout"><span class="oi oi-account-logout"></span> Logout</a>
<?php else: ?>
			<a class="nav-link<?php if ($localuri === "/login") echo " active" ?>" href="/~S151204/login"<?php if ($localuri !== "\/login") echo ' id="login"' ?>><span class="oi oi-account-login"></span> Login</a>
<?php endif; ?>
		</div>
	</div>
</div>
</nav>
<!-- htmlmin:ignore --><div class="body"><div class="container"><!-- htmlmin:ignore -->
<div class="alerts">
	<noscript><div class="alert alert-warning" role="alert">WARNING: JavaScript is not turned on, this site require it to add interactive content to the webpage.</div></noscript>
</div>