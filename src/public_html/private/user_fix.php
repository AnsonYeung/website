<?php
$hash_method = "sha256";
$accounts = json_decode(file_get_contents('../databases/accounts.json'), true);
echo htmlspecialchars(json_encode($accounts)), "<br><br>";
foreach ($accounts as $id => $data) {
	$accounts[$id]["isDeleted"] = false;
}
$result = json_encode($accounts);
echo $result;
// file_put_contents('../databases/accounts.json', $result);
?>