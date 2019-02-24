<?php
include_once "../../php/main.php";
$dir = array_key_exists("dir", $_GET) ? $_GET["dir"] : "/home/student/y2015/S151204/public_html";
if (!is_dir($dir)) {
	if (is_file($dir)) {
		die(header("Location: editor?dir=".$dir, 302));
	}
	$result = "Path is not a directory nor a file";
} else {
	$result = "";
	$files = @scandir($dir);
	if ($files === FALSE) {
		$result = "No Permission";
	} else if (count($files) === 2) {
		$result = '<div class="text-center"><em>Empty folder</em></div>';
	} else {
		$result = '<table class="table table-hover"><thead><tr><th>Type</th><th>Name</th><th>Permission</th><th>Last Modified</th></tr></thead><tbody>';
		$ts = array(
			0140000 => 'ssocket',
			0120000 => 'llink',
			0100000 => '-file',
			0060000 => 'bblock',
			0040000 => 'ddir',
			0020000 => 'cchar',
			0010000 => 'pfifo'
		);
		clearstatcache();
		foreach ($files as $filename) {
			if (($filename !== '.') && ($filename !== '..')) {
				$path = realpath($dir).($dir !== "/" ? "/" : "").$filename;
				$s = @stat($path);
				// $file = BigFileTools::createDefault()->getFile($path);
				if ($s) {
					$p = $s['mode'];
					$t = decoct($p & 0170000); // File Encoding Bit
					$perm = (array_key_exists(octdec($t), $ts)) ? $ts[octdec($t)]{0} : 'u';
					$perm .= (($p & 0x0100) ? 'r' : '-').(($p & 0x0080) ? 'w' : '-');
					$perm .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x') : (($p & 0x0800) ? 'S' : '-'));
					$perm .= (($p & 0x0020) ? 'r' : '-').(($p & 0x0010) ? 'w' : '-');
					$perm .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x') : (($p & 0x0400) ? 'S' : '-'));
					$perm .= (($p & 0x0004) ? 'r' : '-').(($p & 0x0002) ? 'w' : '-');
					$perm .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x') : (($p & 0x0200) ? 'T' : '-'));
					$result .= '<tr class="'.((octdec($t) === 0040000) ? 'dir' : 'file').'" href="?dir='.rawurlencode($path).
						'"><td>'.substr($ts[octdec($t)], 1).'</td><td>'.
							$filename.'</td><td>'.$perm.'</td><td>'.date(DATE_ATOM, $s['mtime']).'</td></tr>';
				} else {
					$result .= '<tr class="file" href="?dir='.rawurlencode($path).
						'"><td></td><td>'.$filename.'</td><td>u---------</td><td>Unable to stat file</td></tr>';
				}
			}
		}
		$result .= '</tbody></table>';
	}
}
include_once "../../php/head.php"; ?>
<title>File Explorer - YSH</title>
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
.window > .browser {
	height: 500px;
	overflow-y: auto;
}
.dir:hover, .file:hover {
	cursor: pointer;
}
.selected {
	background-color: rgba(0, 0, 0, 0.1);
}
.browser tr.selected:hover {
	background-color: rgba(0, 0, 0, 0.175);
}
</style>
<script nonce="<?php echo $script_nonce ?>" type="module">
import { YSH } from "../scripts/main.js";
if (location.search === "") {
	history.replaceState({}, "Server Editor - YSH", "?dir=/home/student/y2015/S151204/public_html");
}
YSH.jQueryPromise.then(function () {
	$("#back").click(history.back.bind(history));
	$("#forward").click(history.forward.bind(history));
	$("#parent").click(() => {
		location.href = "?dir=<?php echo dirname($dir); ?>";
	});
	$("#dir").val(decodeURIComponent(location.search.split("dir=")[1].split("&")[0]));
	$("#dir").on("keydown", e => {
		if (e.keyCode === 13) {
			location.href = "?dir=" + $("#dir").val()
		}
	});
	$(".dir").on("dblclick", e => {
		location.href = e.currentTarget.getAttribute("href");
	});
	$(".file").on("dblclick", e => {
		location.href = "editor" + e.currentTarget.getAttribute("href");
	});
	let selected = null;
	$(".dir,.file").on("click", e => {
		if (selected) selected.classList.remove("selected");
		selected = e.currentTarget;
		selected.classList.add("selected");
	})
});
</script>
<?php include "../../php/navbar.php" ?>
<h1 class="border border-left-0 border-right-0 border-top-0 hscroll mb-3 mt-5 pb-2 text-center d-none d-md-block">=== FILE EXPLORER ===</h1>
<div class="d-md-none mb-3"></div>
<div class="window">
	<div class="topbar">
		<span id="back" class="btn oi oi-arrow-left" title="Previous"></span>
		<span id="forward" class="btn oi oi-arrow-right" title="Forward"></span>
		<span id="parent" class="btn oi oi-arrow-top" title="Parent Directory"></span>
		<input id="dir" class="form-control" type="text" value="<?php echo $dir ?>">
	</div>
	<div class="browser">
<?php echo $result ?>
	</div>
</div>
<?php include "../../php/footer.php" ?>