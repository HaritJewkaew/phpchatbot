<?php
function sendMessage($replyJson, $token) {
    $curl = curl_init();
    if ($curl === false) {
        error_log('Failed to initialize cURL session');
        return ['result' => 'E', 'message' => 'cURL initialization failed'];
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => $token['URL'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $replyJson,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $token['AccessToken'],
            "Cache-Control: no-cache",
            "Content-Type: application/json; charset=UTF-8"
        ),
        CURLOPT_SSL_VERIFYPEER => true
    ));

    $result = curl_exec($curl);
    if ($result === false) {
        $error = curl_error($curl);
        curl_close($curl);
        error_log("cURL error: $error");
        return ['result' => 'E', 'message' => $error];
    }

    curl_close($curl);
    error_log("API response: $result");

    return $result;
}

function getUserProfile($userId, $token) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.line.me/v2/bot/profile/" . $userId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $token['AccessToken'],
        ),
    ));
    $response = curl_exec($curl);
    if ($response === false) {
        error_log('Failed to fetch user profile: ' . curl_error($curl));
        curl_close($curl);
        return null;
    }
    curl_close($curl);
    return json_decode($response, true);
}

function ensureNonEmpty($value, $default = "ไม่ระบุ") {
    return !empty($value) ? $value : $default;
}

function sendErrorMessage($replyToken, $message) {
    $replyMessage = [
        [
            "type" => "text",
            "text" => $message
        ]
    ];

    $replyJson = json_encode([
        "replyToken" => $replyToken,
        "messages" => $replyMessage
    ]);

    sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
}
?>
