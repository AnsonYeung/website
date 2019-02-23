<?php
include "../php/main.php";
$ALLOW_QUERY_ID = TRUE;
include "../php/session.php";
if ($ip === "119.246.80.203" && !isset($username)) {
	// auto log in
	// FIXME: dup with account.php
	// possible: make a const value for this / have a function in main that logs the user in.
	// generate error when needed is a responsibility for ~php/main.php
	$_SESSION["151204-username"] = "admin";
	$username = "admin";
}
session_commit();
isset($username) or ban();
$user_id = strtoupper($username);
if (isset($_GET["page"])) {
	$accounts = json_decode(file_get_contents("databases/accounts.json"), true);
	$user_profile = getorban($accounts, $user_id);
	$priv = $user_profile["privilegeLevel"];
	$priv >= 1 or ban();
	$priv_text = array("none", "elevated", "admin", "root");
	$user_count = 0;
	foreach ($accounts as $id => $data) {
		if ($user_count === $_GET["page"] * 10 + 10) {
			die();
		}
		$targetpriv = $data["privilegeLevel"];
		if ($user_count >= $_GET["page"] * 10) {
			echo "<tr", $data["isDeleted"] ? " class='danger'" : "", "><td><input type='checkbox' value='", $data["name"], "'",
				($priv <= $targetpriv or $data["isDeleted"]) ? " disabled" : "", " /></td><td>",
				$data["name"], $data["name"] === $username ? "<small class='text-primary'>(you)</small>" : "", "</td><td>",
				$data["score"], "</td><td>",
				$priv_text[$targetpriv], "</td></tr>", PHP_EOL;
		}
		$user_count++;
	}
	die();
}
if (isset($_GET["EventSource"])) {
	$accounts = json_decode(file_get_contents("databases/accounts.json"), true);
	$user_profile = getorban($accounts, $user_id);
	$priv = $user_profile["privilegeLevel"];
	$priv >= 1 or ban();
	switch ($_GET["EventSource"]) {
	case "access":
		header("Access-Control-Allow-Origin: http://student.tanghin.edu.hk");
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		ob_implicit_flush();
		$mtime = isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0;
		$sep = "</td><td>";
		echo "retry: 100", PHP_EOL;
		while (connection_status() === CONNECTION_NORMAL) {
			clearstatcache();
			if ($mtime !== filemtime("/var/www/logs/access_log")) {
				$mtime = filemtime("/var/www/logs/access_log");
				echo "id: ", $mtime, PHP_EOL, "event: update", PHP_EOL;
				$arr = explode(PHP_EOL, tail_custom("/var/www/logs/access_log", isset($_GET['rows']) ? 2 * (int)$_GET['rows'] : 40));
				for ($i = count($arr) - 1; $i >= 0; $i -= 2) {
					echo "data: <tr><td>", strtok($arr[$i], " "), $sep, strtok(" "), $sep, strtok(" "), $sep, substr(strtok("]"), 1), $sep; strtok("\"");
					echo strtok("\""), $sep, strtok(" "), $sep, strtok(" "), $sep, strtok("\""), $sep; strtok("\"");
					echo strtok("\""), "</td></tr>", PHP_EOL;
				}
				echo PHP_EOL;
				ob_flush();
			}
			usleep(100000);
		}
		break;
	case "error":
		header("Access-Control-Allow-Origin: http://student.tanghin.edu.hk");
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		ob_implicit_flush();
		$mtime = isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0;
		echo "retry: 100", PHP_EOL;
		while (connection_status() === CONNECTION_NORMAL) {
			clearstatcache();
			if ($mtime !== filemtime("/var/www/logs/error_log")) {
				$mtime = filemtime("/var/www/logs/error_log");
				echo "id: ", $mtime, PHP_EOL, "event: update", PHP_EOL;
				$arr = explode(PHP_EOL, tail_custom("/var/www/logs/error_log", isset($_GET['rows']) ? (int)$_GET['rows'] : 20));
				for ($i = count($arr) - 1; $i >= 0; $i--) {
					echo "data: <tr><td>", $arr[$i], "</td></tr>", PHP_EOL;
				}
				echo PHP_EOL;
				ob_flush();
			}
			usleep(100000);
		}
		break;
	case "slog":
		header("Access-Control-Allow-Origin: http://student.tanghin.edu.hk");
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		ob_implicit_flush();
		$mtime = isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0;
		echo "retry: 100", PHP_EOL;
		while (connection_status() === CONNECTION_NORMAL) {
			clearstatcache();
			if ($mtime !== filemtime("databases/server.log")) {
				$mtime = filemtime("databases/server.log");
				echo "id: ", $mtime, PHP_EOL, "event: update", PHP_EOL;
				$arr = explode(PHP_EOL.PHP_EOL, tail_custom("databases/server.log", isset($_GET['rows']) ? (int)$_GET['rows'] : 20));
				for ($i = count($arr) - 1; $i >= 0; $i--) {
					echo "data: <tr><td>", $arr[$i], "</td></tr>", PHP_EOL;
				}
				echo PHP_EOL;
				ob_flush();
			}
			usleep(100000);
		}
		break;
	}
	ban();
}
include "../php/head.php";
$user_profile = getorban($accounts, $user_id);
$priv = $user_profile["privilegeLevel"];
$priv >= 1 or ban();
$priv_text = array("none", "elevated", "admin", "root");
?>
<title>Admin tools - YSH</title>
<style nonce="<?php echo $style_nonce ?>">
<?php include "style/bootstrap.inline.css" ?>

.error {
	height: 500px;
}

.access {
	height: 500px;
}

.slog {
	height: 500px;
}

</style>
<script src="/~S151204/scripts/admin.js" type="module" async></script>
<?php include "../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Administrative tools</h1></header>
<div class="lead">
<p>Your permission level is: <b><?php echo $priv_text[$priv];?></b></p>
<pre>Session Data: <?php var_dump($_SESSION) ?></pre>
</div>
<h2>Users</h2>
<div class="d-flex pb-2">
	<div>
		<button class="btn btn-default" id="up">Upgrade</button>
		<button class="btn btn-default"<?php if ($priv < 2) echo " disabled"; else echo " id='down'"; ?>>Downgrade</button>
		<button class="btn btn-danger"<?php if ($priv < 3) echo " disabled"; else echo " id='del'"; ?>>Delete</button>
	</div>
	<div class="ml-auto">
		<button class="btn btn-light" id="uprev" disabled><span class="oi oi-chevron-left"></span></button>
		<span>Page <span id="curr-page">1</span> / <span id="total-page"><?php echo ceil(count($accounts) / 10); ?></span></span>
		<button class="btn btn-light" id="unext" disabled><span class="oi oi-chevron-right"></span></button>
	</div>
</div>
<table class="table table-hover">
<thead>
	<tr>
		<th><input type="checkbox" id="sa" /></th>
		<th>Username</th>
		<th>Score</th>
		<th>Privilege</th>
	</tr>
</thead>
<tbody id="user">
<?php
$user_count = 0;
foreach ($accounts as $id => $data) {
	if ($user_count === 10) {
		break;
	}
	$targetpriv = $data["privilegeLevel"];
	echo "<tr", $data["isDeleted"] ? " class='danger'" : "", "><td><input type='checkbox' value='", $data["name"], "'",
		($priv <= $targetpriv or $data["isDeleted"]) ? " disabled" : "", " /></td><td>",
		$data["name"], $data["name"] === $username ? "<small class='text-primary'>(you)</small>" : "", "</td><td>",
		$data["score"], "</td><td>",
		$priv_text[$targetpriv], "</td></tr>";
	$user_count++;
}
?>
</tbody>
</table>
<h2>Error Log <small>(real time)</small></h2>
<table class="table table-hover d-block hscroll error">
	<tbody id="error">
<?php
$arr = explode(PHP_EOL, tail_custom("/var/www/logs/error_log", isset($_GET['error']) ? (int)$_GET['error'] : 20));
for ($i = count($arr) - 1; $i >= 0; $i--) {
	echo "<tr><td>", $arr[$i], "</td></tr>";
}
?>
	</tbody>
</table>
<h2>Access Log <small>(real time)</small></h2>
<table class="table table-hover d-block hscroll access">
	<thead>
		<tr>
			<th>IP</th>
			<th>Bytes received</th>
			<th>User</th>
			<th>Time</th>
			<th>First line of request</th>
			<th>Status Code</th>
			<th>Bytes responsed (exclude header)</th>
			<th>Referer</th>
			<th>User-Agent</th>
		</tr>
	</thead>
	<tbody id="access">
<?php
$arr = explode(PHP_EOL, tail_custom("/var/www/logs/access_log", isset($_GET['access']) ? (int)$_GET['access'] : 20));
$sep = "</td><td>";
for ($i = count($arr) - 1; $i >= 0; $i--) {
echo "<tr><td>", strtok($arr[$i], " "), $sep, strtok(" "), $sep, strtok(" "), $sep, substr(strtok("]"), 1), $sep; strtok("\"");
echo strtok("\""), $sep, strtok(" "), $sep, strtok(" "), $sep, strtok("\""), $sep; strtok("\"");
echo strtok("\""), "</td></tr>";
}
?>
	</tbody>
</table>
<h2>S151204's Server Log <small>(real time)</small></h2>
<table class="table table-hover d-block hscroll slog">
	<tbody id="slog">
<?php
$arr = explode(PHP_EOL.PHP_EOL, tail_custom("databases/server.log", isset($_GET['slog']) ? (int)$_GET['slog'] : 20));
for ($i = count($arr) - 1; $i >= 0; $i--) {
	echo "<tr><td>", $arr[$i], "</td></tr>";
}
?>
	</tbody>
</table>
<div class="container">
<?php include "../php/footer.php" ?>
