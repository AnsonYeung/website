<?php include "../php/head.php"; if (isset($username)): ?><script>location = (sessionStorage.continue || "/~S151204/") + "?";</script><?php endif; ?>
<title>Sign Up - YSH</title>
<style nonce="<?php echo $style_nonce ?>">
<?php include "style/bootstrap.inline.css" ?>
</style>
<script nonce="<?php echo $script_nonce ?>">safeReq(['signup']);</script>
<?php include "../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Register</h1></header>
<div class="row">
	<article class="col-12">
		<h2 class="text-center">Register an account to get more access to this workshop!</h2>
		<form class="form-horizontal" action="user/signup" method="POST">
			<div class="form-group row">
				<label class="control-label col-sm-2" for="username">Username:</label>
				<div class="col-sm-10">
					<input class="form-control" id="username" name="username" autocomplete="username" required />
				</div>
			</div>
			<div class="form-group row">
				<label class="control-label col-sm-2" for="password">Password:</label>
				<div class="col-sm-10">
					<input class="form-control" id="password" name="password" type="password" autocomplete="new-password" required />
				</div>
			</div>
			<p>Have a account already? <a href="login">Sign in now!</a></p>
			<input class="btn btn-light form-control" type="submit"/>
			<output class="hide text-danger"></output>
		</form>
	</article>
</div>
<?php include "../php/footer.php" ?>
