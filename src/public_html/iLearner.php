<?php
header("Cache-Control: no-cache");
require "../php/main.php";
$db = new Database("iLearner");
if (array_key_exists("view", $_GET)) {
	echo "<h1>Submited urls:</h1>";
	foreach ($db->read() as $qstr) {
		$url = "http://reading.tanghin.edu.hk/htmlexercise/TangHin_exercise_page.php?".$qstr;
		echo "<a href='" . $url . "'>" . $qstr . "</a><br>";
	}
} else {
	$iLearner = $db->lock();
	strlen($_SERVER["QUERY_STRING"]) !== 0 and $iLearner[] = $_SERVER["QUERY_STRING"];
	$db->unlock($iLearner);
}
?>