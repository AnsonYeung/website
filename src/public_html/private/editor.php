<?php
include_once "../../php/main.php";
if (array_key_exists("get", $_GET)) {
	header("Content-Type: application/octet-stream");
	die(fpassthru(fopen($_GET["get"], "r")));
}
if (array_key_exists("set", $_GET)) die(file_put_contents($_GET["set"], file_get_contents("php://input")));
$file = array_key_exists("dir", $_GET) ? $_GET["dir"] : "/home/student/y2015/S151204/public_html/index.php";
if (!is_file($file)) {
	if (is_dir($file)) {
		die(header("Location: explorer?dir=".$file, 302));
	}
	$result = "Path is not a file nor a directory";
} else {
	$result = substr(sprintf('%o', fileperms($file)), -4) . "FILE EDITOR IS CURRENTLY IN DEVELOPMENT\n";
}
include_once "../../php/head.php";
header_remove("Content-Security-Policy");
?>
<title>Server Editor - YSH</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/ace.js" async defer id="acejs"></script>
<style nonce="<?php echo $style_nonce ?>">
<?php include "../style/bootstrap.inline.css" ?>
.window {
	border: 1px solid cornflowerblue;
}
.window > .topbar {
	display: flex;
}
.topbar > .btn {
	top: 0;
	color: white;
	background-color: cornflowerblue;
	border-color: cornflowerblue;
}
.topbar > .form-control {
	border-color: cornflowerblue;
}
.topbar > .btn, .topbar > .form-control {
	border-radius: 0;
}
.topbar > .btn:hover, .topbar> .form-control:hover {
	border-color: #80bdff;
	cursor: pointer;
}
#dir {
	cursor: text;
}
.topbar > .btn:hover {
	color: white;
	background-color: black;
}
#editor {
	height: 500px;
}
</style>
<script nonce="<?php echo $script_nonce ?>" type="module">
import { YSH } from "../scripts/main.js";
if (location.search === "") {
	history.replaceState({}, "Server Editor - YSH", "?dir=/home/student/y2015/S151204/public_html/index.php");
}

const acePromise = new Promise(resolve => {
	if (typeof ace !== "undefined") {
		resolve();
	} else {
		document.getElementById("acejs").addEventListener("load", function onAceLoaded() {
			document.getElementById("acejs").removeEventListener("load", onAceLoaded);
			resolve();
		});
	}
});

acePromise.then(function () {
	const editor = ace.edit("editor", {
		mode: "ace/mode/text",
		showPrintMargin: false
	});
	editor.session.setUseWrapMode(true);
	const onResize = function () {
		const session = editor.session;
		editor.resize();
		if (session.getUseWrapMode()) {
			const characterWidth = editor.renderer.characterWidth;
			const contentWidth = editor.renderer.scroller.clientWidth;
			if (contentWidth > 0) {
				session.setWrapLimit(parseInt(contentWidth / characterWidth - 5, 10));
			}
		}
	}
	window.addEventListener("resize", onResize);
	onResize();
	window.editor = editor;
	if (window.bigFile) {
		editor.setValue(bigFile);
		delete window.bigFile;
	}
});
YSH.jQueryPromise.then(() => {
	$("#back").click(history.back.bind(history));
	$("#forward").click(history.forward.bind(history));
	$("#parent").click(() => {
		location = "explorer?dir=<?php echo rawurlencode(dirname($file)); ?>";
	});
	$("#write").click(() => {
		$.post("?set=<?php echo rawurlencode($file); ?>", editor.getValue(), function () {
			location.reload();
		}).catch(alert);
	})
	$("#dir").val(decodeURIComponent(location.search.split("dir=")[1].split("&")[0]));
	$("#dir").on("keydown", e => {
		if (e.keyCode === 13) {
			location.href = "?dir=" + $("#dir").val()
		}
	});
	$.get("?get=<?php echo rawurlencode($file); ?>", (bigFile) => {
		acePromise.then(function () {
			if (window.editor) {
				editor.setValue(bigFile);
			} else {
				window.bigFile = bigFile;
			}
		});
	});
});

</script>
<?php include "../../php/navbar.php" ?>
<h1 class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2 text-center d-none d-md-block">=== SERVER EDITOR ===</h1>
<div class="d-md-none mb-3"></div>
<div class="window">
	<div class="topbar">
		<span id="back" class="btn oi oi-arrow-left" title="Previous"></span>
		<span id="forward" class="btn oi oi-arrow-right" title="Forward"></span>
		<span id="parent" class="btn oi oi-arrow-top" title="Parent Directory"></span>
		<span id="write" class="btn oi oi-pencil" title="Upload File"></span>
		<input id="dir" class="form-control" type="text" value="<?php echo $file ?>">
	</div>
	<div class="browser">

<?php echo $result ?>
<div id="editor"></div>

	</div>
</div>
<?php include "../../php/footer.php" ?>