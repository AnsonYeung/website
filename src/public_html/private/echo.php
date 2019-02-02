<link rel=stylesheet href=table.css><div class=table-title><h3>$_GET array attribute:</h3></div><table class=table-fill><thead><tr><th>Key</th><th>Value</th></tr></thead><tbody><?php
echo "<tr><td>Current PHP version</td><td>".phpversion()."</td></tr>";
foreach ($_GET as $sKey => $sVal) {
	if (gettype($sVal) === "array") {
		$temp = 0;
		foreach ($sVal as $sValVal) {
			echo "<tr><td>{$sKey}[$temp]</td><td>$sValVal</td></tr>";
			$temp++;
		}
	} else {
		echo "<tr><td>$sKey</td><td>$sVal</td></tr>";
	}
}
?></tbody></table>