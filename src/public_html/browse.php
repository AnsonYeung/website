<?php
$a = file_get_contents($_GET['url']);
if ($a) {
	echo $a;
} else {
	echo "Tips:https://www.google.com.hk/search?q=%s<br><form>URL:<input name=url><input type=submit>";
}
?>