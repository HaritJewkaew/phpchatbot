<?php
if (function_exists('curl_init')) {
    echo "cURL is enabled on this server.";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://httpbin.org/get");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    if ($output === false) {
        echo "Error in cURL request: " . curl_error($ch);
    } else {
        echo "cURL request successful.";
    }
    curl_close($ch);
} else {
    echo "cURL is not enabled on this server.";
}
?> 
