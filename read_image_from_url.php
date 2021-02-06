<?php
$imageUrl = file_get_contents("php://input");
$imageDetails = [];

$imageData = "";
$counter  = 1;
while (!$imageData && $counter <= 4) {
    $imageData = @file_get_contents($imageUrl);
	$counter++;
}

$imageType = pathinfo($imageUrl, PATHINFO_EXTENSION);

$imageDetails['type'] = $imageType;
$imageDetails['data'] = base64_encode($imageData);

print_r(json_encode($imageDetails));