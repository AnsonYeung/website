<?php

// WebSocket

include "../../php/main.php";
if (strpos(strtoupper($_SERVER["HTTP_CONNECTION"]), "UPGRADE") !== FALSE && strpos(strtoupper($_SERVER["HTTP_UPGRADE"]), "WEBSOCKET") !== FALSE && isset($_SERVER["HTTP_SEC_WEBSOCKET_KEY"])) {
	if ($_SERVER["HTTP_SEC_WEBSOCKET_VERSION"] !== "13") {
		header("HTTP/1.1 400 Bad Request");
		header("Sec-WebSocket-Version: 13");
		die();
	}
	// Initiate the websocket connection
	header("HTTP/1.1 101 Switching Protocols");
	header("Content-Type: text/plain");
	header("Connection: upgrade");
	header("Content-Encoding: utf-8");
	header("Upgrade: websocket");
	header("Sec-WebSocket-Accept: " . base64_encode(sha1($_SERVER["HTTP_SEC_WEBSOCKET_KEY"] . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", TRUE)));
	ob_implicit_flush();
	define("STDIN", fopen("php://input", "r"));
	while (!file_exists(".KILL")) {
		$str = stream_get_contents(STDIN);
		outputtext("size: ".strlen($str));
		if ($str !== "") {
			slog("WebSocket data received: ", $str);
			outputtext("= Received =");
		}
		sleep(1);
	}
	slog(json_encode(stream_get_meta_data(fopen("php://input", "r"))));
} else ban();

function outputtext($str) {
	if (strlen($str) < 126) {
		echo "\x81", chr(strlen($str)), $str;
		ob_flush();
	} else {
		throw new Error("The length is too long to handle by this moment");
	}
}
?>