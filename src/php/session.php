<?php
require_once("main.php");
if (isset($ALLOW_QUERY_ID) && $ALLOW_QUERY_ID && isset($_GET[session_name()])) {
    session_id($_GET[session_name()]);
}
session_set_cookie_params(1800, "/~S151204");
session_start();
$ip = isset($_SERVER["HTTP_FORWARDED"]) ? substr($_SERVER["HTTP_FORWARDED"], 4) : $_SERVER["REMOTE_ADDR"];
if (!isset($_SESSION["CREATED"]) or !isset($_SESSION["IP"])) {
    $_SESSION = array("CREATED" => time(), "IP" => $ip);
    $_SESSION["CREATED"] = time();
    $_SESSION["IP"] = $ip;
    if ($ip === "10.0.7.2") {
        slog("IP 10.0.7.2 was detected, no action was taken.");
    }
} else if ($_SESSION["IP"] !== $ip and $ip !== "10.0.7.2") {
    session_regenerate_id();
    error_log("S151204: php/session.php error: old IP " . $_SESSION["IP"] . " switched to new IP " . $ip);
    $_SESSION = array("CREATED" => time(), "IP" => $ip);
} else if (time() - $_SESSION["CREATED"] > 1800) {
    session_regenerate_id(TRUE);
    $_SESSION["CREATED"] = time();
}
if (isset($_SESSION["151204-username"])) {
    $username = $_SESSION["151204-username"];
}
?>