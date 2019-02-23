<?php
require "../../../php/main.php";
$pss = new Database("pss");
$msg = getorban($_GET, "msg");
$rm = substr($msg, 0, 4);
$sec = substr($msg, 4, 4);
$data = $pss->lock();
if (!isset($data[$rm])) {
	$data[$rm] = array("game"=>array(),"user"=>array($sec), "count"=>0);
	$pss->unlock($data);
	die("0");
}
$rmdata = $data[$rm];
$user = substr($msg, 8, 1);
if ($user === "0" && strlen($msg) === 9) {
	$pss->unlock($data);
	if (count($rmdata["user"]) > 1) {
		die("start");
	}
	$fsize = filesize("../../databases/pss.json");
	$fmtime = filemtime("../../databases/pss.json");
	while (connection_status() === CONNECTION_NORMAL) {
		clearstatcache();
		if ($fsize !== filesize("../../databases/pss.json") || $fmtime !== filemtime("../../databases/pss.json")) {
			$data = $pss->read();
			if (count($data[$rm]["user"]) > 1) {
				die("start");
			}
		}
		usleep(100000);
	}
}
if (count($rmdata["user"]) === 1) {
	$data[$rm]["user"][1] = $sec;
	$data[$rm]["game"][0] = array();
	$pss->unlock($data);
	die("1");
} else if (strlen($msg) === 8) {
	$pss->unlock($data);
	die("no spectator");
}
if ($rmdata["user"][$user] !== $sec) {
	$pss->unlock($data);
	die("Incorrect secure code.");
}
$act = substr($msg, 9, 1);
$opponent = $user === "0" ? 1 : 0;
if (isset($rmdata["game"][count($rmdata["game"]) - 1][$opponent])) {
	$data[$rm]["game"][count($rmdata["game"]) - 1][$user] = $act;
	if ($rmdata["game"][count($rmdata["game"]) - 1][$opponent] !== $act) {
		$data[$rm]["count"]++;
	}
	$data[$rm]["game"][count($rmdata["game"])] = array();
	$pss->unlock($data);
	die($rmdata["game"][count($rmdata["game"]) - 1][$opponent]);
} else {
	$data[$rm]["game"][count($rmdata["game"]) - 1][$user] = $act;
	$pss->unlock($data);
	$g = count($rmdata["game"]) - 1;
	$fsize = filesize("../../databases/pss.json");
	$fmtime = filemtime("../../databases/pss.json");
	while (connection_status() === CONNECTION_NORMAL) {
		clearstatcache();
		if ($fsize !== filesize("../../databases/pss.json") || $fmtime !== filemtime("../../databases/pss.json")) {
			$data = $pss->read();
			if (isset($data[$rm]["game"][count($rmdata["game"]) - 1][$opponent])) {
				$temp = $data[$rm]["game"][count($rmdata["game"]) - 1][$opponent];
				if ($data[$rm]["count"] >= 5) {
					$data = $pss->lock();
					unset($data[$rm]);
					$pss->unlock($data);
				}
				die($temp);
			}
		}
		usleep(100000);
	}
}
die("?");
?>