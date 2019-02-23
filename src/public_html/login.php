<?php include_once "../php/head.php"; if (isset($username)): ?>
<script nonce="<?php echo $script_nonce ?>">location = (sessionStorage.continue || "/~S151204/profile/" + sessionStorage.username) + "?";</script>
<?php endif; ?>
<title>Login - YSH</title>
<style nonce="<?php echo $style_nonce ?>">
@import url('https://fonts.googleapis.com/css?family=Roboto:500');

<?php include "style/bootstrap.inline.css" ?>

#gsignin {
	height: 40px;
	display: inline-flex;
	justify-content: center;
	padding: 10px 8px;
	box-shadow: 0 2px 4px 0 rgba(0,0,0,.25);
	transition: 0.218s;
}

#gsignin:hover {
	box-shadow: 0 0 3px 3px rgba(66, 133, 244, 0.3);
	cursor: pointer;
}

#gsignin > span {
	height: 18px;
	font-family: "Roboto", sans-serif;
	font-size: 14px;
	padding-left: 24px;
}
</style>
<script src="https://apis.google.com/js/platform.js" async id="gapi"></script>
<script src="/~S151204/scripts/login.js" type="module" async></script>
<?php include "../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Login</h1></header>
<div class="row">
	<article class="col-12">
		<h2 class="text-center">Login to S151204's workshop to get your privileges!</h2>
		<form class="form-horizontal" action="user/login" method="POST">
			<div class="form-group row">
				<label class="control-label col-sm-2" for="username">Username:</label>
				<div class="col-sm-10">
					<input class="form-control" id="username" name="username" autocomplete="username" required />
				</div>
			</div>
			<div class="form-group row">
				<label class="control-label col-sm-2" for="password">Password:</label>
				<div class="col-sm-10">
					<input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required />
				</div>
			</div>
			<p>No account yet? <a href="signup">Register now!</a></p>
			<input class="btn btn-light form-control" type="submit"/>
			<output class="hide text-danger"></output>
		</form>
		<h4>- OR -</h4>
		<div id="gsignin">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48">
				<g>
					<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
					<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
					<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
					<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
					<path fill="none" d="M0 0h48v48H0z"></path>
				</g>
			</svg>
			<span>Sign in with Google</span>
		</div>
		<p class="d-none" id="wait">Please wait while we're signing you in.</p>
	</article>
</div>
<?php include "../php/footer.php" ?>
