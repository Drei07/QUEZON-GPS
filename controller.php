<?php

$proxyServerUrl = 'https://aquasense.online/dashboard/admin/controller/gsm.php'; // Replace with your proxy server URL

$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'wifi_status' => 'No device found',
        'gpsCoordinates' => 'No Coordinates',
        'latitude' => null,
        'longitude' => null,
        'speed' => null,
        'satellites' => null
    ]);

    error_log("Failed to fetch data from proxy server.");
}


?>
