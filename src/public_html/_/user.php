<?php

include_once "../../php/main.php";
include_once "../../php/head.php";
$user_id = strtoupper(getorban($_GET, 'username'));
isset($accounts[$user_id]) or ban();
$user_profile = $accounts[$user_id];
$user_name = $user_profile["name"];
$user_priv = $user_profile["privilegeLevel"];
if (isset($_SESSION['151204-username']) and $_SESSION['151204-username'] === $user_name) {
	include "../../php/session.php";
	$_SESSION["csrf-id"] = "gbind";
	$_SESSION["csrf-code"] = bin2hex(openssl_random_pseudo_bytes(32));
	session_commit();
}
?>
<title>User <?php echo $user_name ?> - YSH</title>
<style nonce="<?php echo $style_nonce ?>"<?php echo ">\n"; include __DIR__ . "/../style/bootstrap.inline.css"; echo "/* Page specific CSS "; ?>>/**/
@import url('https://fonts.googleapis.com/css?family=Roboto:500');

h1 {
	text-align: center;
}

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
<?php include "../../php/navbar.php" ?>
<?php if (isset($_SESSION['151204-username']) and $_SESSION['151204-username'] === $user_name) { ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Your user profile</h1></header>
<?php if ($user_priv >= 1): ?>
<h2>Administrative tools</h2>
<p>A tool to monitor users and monitor the real time data of server error and access log.<a href="../admin">Go now!</a></p>
<?php endif; ?>
<h3 id="gtitle">Bind <?php if (get($user_profile, "gac", "") !== "") echo "another "; ?>google account</h3>
<input type="hidden" id="csrf-code" value="<?php echo $_SESSION['csrf-code'] ?>">
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
<p id="gsuccess">Google account bind successfully.</p>
<script nonce="<?php echo $script_nonce ?>">document.getElementById("gsuccess").style.display="none";safeReq(['profile']);</script>
<p id="wait" class="d-none">Please wait while our server is processing.</p>
<p>This page is under development...</p>
<?php } else { ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>User profile of <?php echo $user_name?></h1></header>
<p>You are viewing another user's profile.</p>
<?php } ?>
<?php include "../../php/footer.php"?>
