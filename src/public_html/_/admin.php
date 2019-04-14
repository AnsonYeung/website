<?php

include "../../php/main.php";
require "../../php/session.php";

$db = new Database("accounts");
$accounts = $db->lock();
$username = getorban($_SESSION, "151204-username");
$user_id = strtoupper($username);
$profile = getorban($accounts, $user_id);
$priv = $profile["privilegeLevel"];
$allowed = array("up", "down", "del");
in_array(getorban($_GET, "action"), $allowed) or ban();
$page = getorban($_POST, "page");
$users = json_decode(getorban($_POST, "users"), true);
switch ($_GET["action"]) {
case "up":
	$priv >= 1 or perf();
	foreach ($users as $user) {
		$id = strtoupper($user);
		$profile = getorban($accounts, $id);
		$priv > $profile["privilegeLevel"] or perf();
		$profile["privilegeLevel"] < 2 or ban();
		$accounts[$id]["privilegeLevel"]++;
	}
	break;
case "down":
	$priv >= 2 or perf();
	foreach ($users as $user) {
		$id = strtoupper($user);
		$profile = getorban($accounts, $id);
		$priv > $profile["privilegeLevel"] or perf();
		$profile["privilegeLevel"] !== 0 or ban();
		$accounts[$id]["privilegeLevel"]--;
	}
	break;
case "del":
	$priv >= 3 or perf();
	foreach ($users as $user) {
		$id = strtoupper($user);
		getorban($accounts, $id);
		$accounts[$id]["isDeleted"] = true;
	}
	break;
}
$db->unlock($accounts);
$user_count = 0;
foreach ($accounts as $id => $data) {
	if ($user_count === $page * 10 + 10) {
		die();
	}
	$targetpriv = $data["privilegeLevel"];
	if ($user_count >= $page * 10) {
		echo_admin_r($data, $priv <= $targetpriv, $data["name"] === $username);
	}
	$user_count++;
}

function perf() {
	die("Permission declined");
}
?>