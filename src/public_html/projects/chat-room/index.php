<?php
if (isset($_GET["type"]) && $_GET["type"] === "EventSource") {
	require "../../../php/main.php";
	$ALLOW_QUERY_ID = TRUE;
	require "../../../php/session.php";
	session_commit();
	isset($username) or ban();
	$db = new Database("chat");
	$chat = $db->lock();

	foreach ($chat["users"] as $key => $val) {
		if (time() - $val > 2) {
			unset($chat["users"][$key]);
		}
	}

	if (isset($_POST["logout"]) and $_POST["logout"] === "true") {
		if (isset($username)) {
			unset($chat["users"][$username]);
			if (count($chat["users"]) === 0) {
				// chat room auto cleaning system
				$chat["log"] = array();
			}
		}
		$db->unlock($chat);
		die();
	}
	
	if (count($chat["users"]) === 0) {
		// chat room auto cleaning system
		$chat["log"] = array();
	}

	if (isset($_POST["message"])) {
		array_push($chat["log"], array("username" => $username, "message" => htmlspecialchars($_POST["message"])));
		$db->unlock($chat);
		die("success");
	}
	
	header("Access-Control-Allow-Origin: http://student.tanghin.edu.hk");
	header("Content-Type: text/event-stream");
	header("Cache-Control: no-transform");
	ob_implicit_flush();
	if (isset($_SERVER["HTTP_VIA"]) and $_SERVER["HTTP_VIA"] === "1.1 Chrome-Compression-Proxy") {
		$db->unlock($chat);
		die();
	}

	if (isset($chat["users"][$username])) {
		$db->unlock($chat);
		die("event: requestClose\ndata: User already using chat room (if it is wrong, please try reload, logout and login back to solve this issue)\n\n");
	}

	$chat["users"][$username] = time();
	$db->unlock($chat);
	$chatCount = count($chat["log"]);
	$chatUsers = array();
	$ping = 0;
	echo "retry: 100", PHP_EOL;
	echo "id: ", $chatCount - 1, PHP_EOL;
	while (connection_status() === CONNECTION_NORMAL) {
		$chat = $db->lock();
		if (!isset($chat["users"][$username])) {
			if (count($chat["users"]) === 0) {
				// chat room auto cleaning system
				$chat["log"] = array();
			}
			$db->unlock();
			die("event: requestClose\ndata: You left the chat room.\n\n");
		}
		$chat["users"][$username] = time();
		foreach ($chat["users"] as $key => $val) {
			if (time() - $val > 2) {
				unset($chat["users"][$key]);
			}
		}
		$db->unlock($chat);
		$curChatUsers = array_keys($chat["users"]);
		if (count(array_diff(array_merge($chatUsers, $curChatUsers), array_intersect($chatUsers, $curChatUsers))) !== 0) {
			foreach (array_diff($chatUsers, $curChatUsers) as $key => $user) {
				echo "event: userleave", PHP_EOL;
				echo "data: {\"username\": \"", addslashes($user), "\"}", PHP_EOL, PHP_EOL;
				array_splice($chatUsers, $key);
			}
			foreach (array_diff($curChatUsers, $chatUsers) as $user) {
				echo "event: userconnect", PHP_EOL;
				echo "data: {\"username\": \"", addslashes($user), "\"}", PHP_EOL, PHP_EOL;
				array_push($chatUsers, $user);
			}
			echo "event: updateUsers", PHP_EOL;
			echo "data: ", json_encode($curChatUsers), PHP_EOL, PHP_EOL;
		}
		$curChatLog = $chat["log"];
		while ($chatCount < count($curChatLog)) {
			echo "id: ", $chatCount, PHP_EOL;
			echo "event: usermessage", PHP_EOL;
			echo "data: ", json_encode($curChatLog[$chatCount]), PHP_EOL, PHP_EOL;
			$chatCount++;
		}
		$ping++;
		if ($ping === 100) {
			echo "data: ping", PHP_EOL;
			$ping = 0;
		}
		echo PHP_EOL;
		ob_flush();
		usleep(100000);
	}
}
include "../../../php/head.php"; if (!isset($username)): ?>
<script nonce="<?php echo $script_nonce?>">sessionStorage.continue = location; location.href = "../login";</script><?php die(); endif; ?>
<title>Chat room - YSH</title>
<meta name="keywords" content="YSH, chat room, computer, programming, javascript" />
<meta name="description" content="Yeung Sin Hang's discussion space." />
<style nonce="<?php echo $style_nonce?>">
<?php include "../../style/bootstrap.inline.css" ?>

#chat-log {
	overflow-y: auto;
	height: 400px;
	border-bottom-right-radius: 0;
	border-bottom-left-radius: 0;
}

#chat-log + .input-group {
	margin-top: -1px;
}

#chat-log + .input-group > .form-control {
	border-top-left-radius: 0;
}

#chat-log + .input-group > * > .btn {
	border-top-right-radius: 0;
}
</style>
<script nonce="<?php echo $script_nonce ?>">safeReq(["chat"]);</script>
<?php include "../../../php/navbar.php" ?>
<header class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2"><h1>Chat room</h1></header>
<div id="online"></div>
<div class="form-control" id="chat-log"></div>
<div class="input-group"><input class="form-control" id="chat"><div class="input-group-btn"><button class="btn btn-default" id="send">Send</button></div></div>
<?php include "../../../php/footer.php"?>
