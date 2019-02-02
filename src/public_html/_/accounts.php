<?php

include "../../php/main.php";
$allowed = array("create", "login", "update", "search", "logout", "glogin", "gbind");
isset($_GET["action"]) and in_array($_GET["action"], $allowed) or ban();
const HASH_METHOD = "sha256";
define("ACTION", $_SERVER["REQUEST_METHOD"] . "-" . $_GET["action"]);
$db = new Database("accounts");
$db_data = $db->read();
$un = get($_REQUEST, "username", "");
$uid = strtoupper($un);
$upw = get($_REQUEST, "password", "");
$noredir = isset($_REQUEST["noredir"]);

if (isset($db_data[$uid])) {
	$upf = $db_data[$uid];
	$correct = $upf["name"] === $un && hash($upf["password"][2], $upf["password"][0] . $upw) === $upf["password"][1];
} else {
	$correct = FALSE;
}

switch (ACTION) {
case "POST-create":
	if (array_key_exists($uid, $db_data)) {
		die("Account already exists!");
	}
	$ul = strlen($un);
	if ($ul < 6 or $ul > 13) {
		die("Username must be longer than 6 characters and shorter than 13 characters!");
	}
	if (htmlspecialchars($un) !== $un) {
		die("Username contains unaccepted characters!");
	}
	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$npf = array(
		"name" => $un,
		"password" => array($salt, hash(HASH_METHOD, $salt . $upw), HASH_METHOD),
		"score" => 0,
		"privilegeLevel" => 0,
		"isDeleted" => FALSE
	);
	$db_data = $db->lock();
	$db_data[$uid] = $npf;
	$db->unlock($db_data);
	login();
	slog("Welcome! New user: ", $un);
	$noredir or header("Location: /~S151204/");
	die("Account created successfully.");
	break;
case "POST-login":
	$login = ($correct and !$upf["isDeleted"]);
	if ($login) {
		if (strlen($upf["password"][0]) !== 64) {
			// Do a upgrade for password hashing
			$salt = bin2hex(openssl_random_pseudo_bytes(32));
			$npw = array($salt, hash(HASH_METHOD, $salt . $upw), HASH_METHOD);
			$db_data = $db->lock();
			$db_data[$uid]["password"] = $npw;
			$db->unlock($db_data);
		}
		slog($un . " logged in.");
		login();
	} else {
		slog($un, " attempted to log in, but failed.");
	}
	$noredir and die($correct ? "true" : "false");
	die(header("Location: " . ($correct ? "\/~S151204/" : "/~S151204/login?error=Password+Incorrect")));
	break;
case "POST-update":
	if (!array_key_exists($uid, $db_data)) {
		die("Account not exists!");
	}
	if (!$correct) {
		die("Password incorrect");
	}
	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$npw = array($salt, hash(HASH_METHOD, $salt . $_POST["new"]), HASH_METHOD);
	$db_data = $db->lock();
	$db_data[$uid]["password"] = $npw;
	$db->unlock($db_data);
	slog("Updated user info for ", $un, ".");
	login();
	$noredir or header("Location: /~S151204/");
	die("Password updated successfully.");
	break;
case "POST-glogin":
	$jwt = getorban($_POST, "jwt");
	$jwt_info = json_decode(file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" . $jwt), TRUE);
	if (get($jwt_info, "error_description", "no error") !== "no error") {
		die("jwt invalid");
	}
	$gid = $jwt_info["sub"];
	$gacdb = new Database("gac");
	$gac_data = $gacdb->read();
	$un = get($gac_data, $gid, "");
	if ($un === "")
		die("need registration");
	$un = getorban($db_data, $un);
	$un = $un["name"];
	login();
	slog($un, " logged in via Google SignIn.");
	$noredir or header("Location: /~S151204/");
	die("success" . $un);
	break;
case "POST-gbind":
	require_once "../../php/session.php";
	session_commit();
	isset($username) or ban();
	$csrf_code = getorban($_POST, "csrf-code");
	$jwt = getorban($_POST, "jwt");
	if (getorban($_SESSION, "csrf-id") !== "gbind" or getorban($_SESSION, "csrf-code") !== $csrf_code) {
		die("csrf code mismatch");
	}
	$jwt_info = json_decode(file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" . $jwt), TRUE);
	if (get($jwt_info, "error_description", "") === "Invalid Value") {
		die("jwt invalid");
	}
	$gid = $jwt_info["sub"];
	$uid = strtoupper($username);
	$gacdb = new Database("gac");
	$gac_data = $gacdb->lock();
	$orgid = get($gac_data, $jwt_info["sub"], "");
	$gac_data[$gid] = $uid;
	$gacdb->unlock($gac_data);
	$db_data = $db->lock();
	$db_data[$uid]["gac"] = $gid;
	if ($orgid !== "" && $orgid !== $uid)
		unset($db_data[$orgid]["gac"]);
	$db->unlock($db_data);
	require "../../php/session.php";
	unset($_SESSION["csrf-id"]);
	unset($_SESSION["csrf-code"]);
	session_commit();
	slog("Gbind for user ", $username);
	die("Gbind success");
	break;
case "GET-search":
	header("Content-Type: application/json");
	if (array_key_exists($uid, $db_data)) {
		unset($db_data[$uid]["password"]);
		die(json_encode($db_data[$uid]));
	} else {
		die("{}");
	}
	break;
case "GET-logout":
	require_once "../../php/session.php";
	session_destroy();
	$chat = new Database("chat");
	$db_data = $chat->lock();
	unset($db_data["users"][get($_SESSION, "151204-username", "")]);
	$chat->unlock($db_data);
	if ($username !== "")
		slog($username, " logged out.");
	$noredir or header("Location: /~S151204/");
	break;
}

function login() {
	require_once "../../php/session.php";
	$_SESSION["151204-username"] = $GLOBALS["un"];
}
?>