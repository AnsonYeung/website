<?php
$read = false;
$list = array();
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST["action"]) && isset($_POST["filename"])) {
		if (file_exists($_POST['filename'])) {
			switch ($_POST['action']) {
			case "read":
				$myfile = fopen($_POST['filename'], "r") or die("Could not open '{$_POST['filename']}'");
				if (file_get_contents($_POST['filename']) !== "") {$read = true;}
				else {fclose($myfile);}
				break;
			case "create":
				if (array_key_exists("data", $_POST)) {
					$myfile = fopen($_POST['filename'], "w") or die("Could not open '{$_POST['filename']}'");
					fwrite($myfile, $_POST['data']);
					fclose($myfile);
					$myfile = fopen($_POST['filename'], "r") or die("Could not open '{$_POST['filename']}'");
					$read = true;
				} else {
					$msg = "NO DATA received to write to the file";
				}
				break;
			case "append":
				if (array_key_exists("data", $_POST)) {
					$myfile = fopen($_POST['filename'], "a") or die("Could not open '{$_POST['filename']}'");
					fwrite($myfile, $_POST['data']);
					fclose($myfile);
				} else {
					$msg = "NO DATA received to write to the file";
				}
				break;
			case "delete":
				if (unlink($_POST['filename'])) {
					$msg = "File '{$_POST['filename']}' is deleted successfully.<br>";
				} else {
					die("Failed to delete file '{$_POST['filename']}' ");
				}
				break;
			case "md":
				mkdir($_POST['filename'], 0755, true) or die("Could not create directory '{$_POST['filename']}'");
				break;
			case "list":
				$list = scandir($_POST['filename']);
				break;
			}
		} else {
			$msg = "file is not found!";
		}
	} else {
		$msg = "POST failed!";
	}
}
include_once "../../php/head.php";
?>
<title>Server Editor - YSH</title>
<style nonce="<?php echo $style_nonce ?>">
<?php include "../style/bootstrap.inline.css" //?>
</style>
<script nonce="<?php echo $script_nonce ?>">
safeReq(["jquery"], function () {
	var $data;
	$("html").on("click", ".no-data", function () {
		$data.hide();
	}).on("click", ".display-data", function () {
		$data.show();
	});
	$(function () {
		$data = $(".data");
		<?php if (!$read) {echo '$data.hide();', PHP_EOL;} ?>
		$('textarea').each(function () {
			$(this).css({height: this.scrollHeight, "overflow-y": "hidden"});
			requestAnimationFrame(() => {
				this.style.height = 'auto';
				$(this).height(this.scrollHeight);
			});
		}).on('input', function () {
			this.style.height = 'auto';
			$(this).height(this.scrollHeight);
		});
		$(".read").click(function () {
			$("input[value=read]").click();
			$("#filename").val(this.value);
		});
		$(".list").click(function () {
			$("input[value=list]").click();
			$("#filename").val(this.value);
		})
	});
});
</script>
<?php include "../../php/navbar.php" ?>
<?php echo $msg ?>
<h2><a href="./">&lt;&lt;BACK</a></h2>
<h1 class="text-center">=== SERVER EDITOR ===</h1>
<form class="form-horizontal" method="POST">
	<div class="form-group">
		<label class="radio-inline"><input class="no-data" name="action" type="radio" value="read" required<?php if (!$read) {echo " checked";}?>>Read</label>
		<label class="radio-inline"><input class="display-data" name="action" type="radio" value="create" required<?php if ($read) {echo " checked";}?>>Create / Write</label>
		<label class="radio-inline"><input class="display-data" name="action" type="radio" value="append" required>Append</label>
		<label class="radio-inline"><input class="no-data" name="action" type="radio" value="delete" required>Delete</label>
		<label class="radio-inline"><input class="no-data" name="action" type="radio" value="md" required>Make Directory</label>
		<label class="radio-inline"><input class="no-data" name="action" type="radio" value="list" required>List file (for directory *BETA feature)</label>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2" for="filename">Filename: </label>
		<div class="col-sm-10"><input class="form-control" id="filename" name="filename" type="text" value="<?php
		echo array_key_exists("filename", $_POST) ? realpath($_POST['filename']) ? realpath($_POST['filename']) : $_POST['filename'] : ""?>" required></div>
	</div>
	<div class="form-group">
		<textarea class="data form-control" name="data"><?php
			if ($read){
				while(!feof($myfile)) {
					echo htmlspecialchars(fgets($myfile));
				}
				fclose($myfile);
			}
		?></textarea>
		<?php
			foreach ($list as $v) {
				$filename = $_POST['filename']."/";
				$isdir = is_dir($filename.$v);
				$newfile = "";
				if ($v !== "." && $v !== "..") {
					$newfile = $filename.$v.($isdir?"/":"");
				} else if ($v === "..") {
					$v = "<img src=\"/icons/back.gif\"> Parent Directory";
					if ($_POST['filename'] !== "/") {
						preg_match("/(.*\/).+/", $_POST['filename'], $newfile);
						$newfile = $newfile[1];
					} else {
						$newfile = "/";
					}
				}
				if ($v !== "."):
		?>
			<button class="btn btn-default <?php echo $isdir ? "list" : "read" ?>" value="<?php echo $newfile ?>"><?php echo $v ?></button><span>(<?php echo $isdir ? "Directory" : "File" ?>)</span><br>
		<?php endif;
			}
		?>
	</div>
	<input type="submit" class="btn btn-default">
</form>
<?php include "../../php/footer.php" ?>
