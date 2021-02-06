<?php
$url = file_get_contents("php://input");
$urlData = "";
while (!$urlData) {
    $urlData = file_get_contents($url);
}
print_r($urlData);