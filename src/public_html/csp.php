<?php
include "../php/main.php";
$_SERVER["REQUEST_METHOD"] === "POST" or ban();
$csp = json_decode(file_get_contents("databases/csp.json"), true);
$target = json_decode(file_get_contents("php://input"), true);
array_push($csp, isset($target["csp-report"]) ? $target["csp-report"] : $target);
file_put_contents("databases/csp.json", formatJSON($csp));
?>