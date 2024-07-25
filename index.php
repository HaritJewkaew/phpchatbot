<?php
include("components/query.php");
include("components/line_function.php");
include("components/flex_message.php");

$LINEData = file_get_contents('php://input');
file_put_contents('log.txt', $LINEData . PHP_EOL, FILE_APPEND);

$jsonData = json_decode($LINEData, true);
$reResult = getRequestResult();

$replyToken = $jsonData["events"][0]["replyToken"] ?? null;
if (!$replyToken) {
    error_log("No replyToken found.");
    return;
}

$text = strtolower($jsonData["events"][0]["message"]["text"] ?? "");

$userId = $jsonData['events'][0]['source']['userId'] ?? null;
$userProfile = null;
$utype = $jsonData['events'][0]['type'] ?? null;

$infoId = null;

$reResult = array_map('ensureNonEmpty', $reResult);

if (isset($jsonData["events"][0]["type"]) && $jsonData["events"][0]["type"] == "postback") {
    handlePostback($jsonData, $reResult, $userId, $replyToken);
} else {
    handleTextMessage($text, $reResult, $replyToken, $jsonData);
}

function handlePostback($jsonData, $reResult, $userId, $replyToken) {
    global $conn;
    parse_str($jsonData["events"][0]["postback"]["data"], $postbackData);
    $action = $postbackData['action'] ?? null;
    if ($action == 'accept_job') {
        error_log("Postback action 'accept_job' triggered.");
        $itemId = $postbackData['itemId'];
        $userProfile = getUserProfile($userId, ['AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
        $displayName = ensureNonEmpty($userProfile['displayName'], 'Unknown User');
        $selectQuery = "SELECT stretcher_register_id FROM stretcher_register WHERE stretcher_register_id = '$itemId'";
        $selectResult = mysqli_query($conn, $selectQuery);
        if ($selectResult) {
            $row = mysqli_fetch_assoc($selectResult);
            if ($row) {
                $infoId = $row['stretcher_register_id'];
                $updateQuery = "UPDATE stretcher_register SET stretcher_work_status_id = 1, ผู้รับ = '$displayName' WHERE stretcher_register_id = '$infoId'";
                try {
                    $updateResult = mysqli_query($conn, $updateQuery);
                } catch (\Throwable $th) {
                    error_log($th->getMessage());
                }

                if ($updateResult) {
                    error_log("Job accepted successfully: " . json_encode($reResult));
                    $responemessage[] = createFlexMessage($reResult, $displayName);

                    $responseJson = json_encode([
                        "replyToken" => $replyToken,
                        "messages" => $responemessage
                    ]);
                    sendMessage($responseJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
                } else {
                    sendErrorMessage($replyToken, "Error updating status for stretcher_register_id '$infoId': " . mysqli_error($conn));
                }
            } else {
                sendErrorMessage($replyToken, "Item ID '$itemId' not found in the database.");
            }
        } else {
            sendErrorMessage($replyToken, "Error executing SQL query: " . mysqli_error($conn));
        }
    } elseif ($action == 'confirm_complete') {
        confirmComplete($postbackData, $replyToken, $userId);
    }
}

function handleTextMessage($text, $reResult, $replyToken, $jsonData) {
    global $conn;
    $replymessage = [];
    switch ($text) {
        case 'a':
            $id = $reResult['ID'];
            $caller = $reResult['Caller'];
            $status = $reResult['status'];
            $location = $reResult['location'];
            $type = $reResult['Type'];

            $message = "ID: $id\nCaller: $caller\nStatus: $status\nLocation: $location\nType: $type";

            $replymessage[] = [
                "type" => "text",
                "text" => $message
            ];
            break;

        case 'ส่งงาน':
            handleSendJob($jsonData, $replymessage);
            break;

        case 'รับงาน':
        case 'r':
            handleReceiveJob($reResult, $replymessage);
            break;

        default:
            $replymessage[] = [
                "type" => "text",
                "text" => "Hello, this is a default reply!"
            ];
            break;
    }

    $lineData = [
        'URL' => "https://api.line.me/v2/bot/message/reply",
        'AccessToken' => "OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU="
    ];
    $replyJson = json_encode([
        "replyToken" => $replyToken,
        "messages" => $replymessage
    ]);

    if ($replyJson === false) {
        error_log("Failed to encode JSON: " . json_last_error_msg());
        return;
    }

    sendMessage($replyJson, $lineData);
}

function handleSendJob($jsonData, &$replymessage) {
    global $conn;
    $userId = $jsonData['events'][0]['source']['userId'] ?? null;
    if ($userId) {
        $userProfile = getUserProfile($userId, ['AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
        $displayName = ensureNonEmpty($userProfile['displayName'], 'Unknown User');
        error_log("Fetched user profile: " . json_encode($userProfile));
    } else {
        $displayName = 'Unknown User';
        error_log("User ID not found");
    }

    error_log("Display Name: $displayName");

    $checkQuery = "SELECT * FROM stretcher_register WHERE ผู้รับ = '$displayName' AND stretcher_work_status_id = 1 LIMIT 1";

    error_log("SQL Query: $checkQuery");

    $checkResult = mysqli_query($conn, $checkQuery);
    if ($checkResult) {
        $row = mysqli_fetch_assoc($checkResult);
        if ($row) {
            $infoId = $row['stretcher_register_id'];
            error_log("Found job: " . json_encode($row));
            $replymessage[] = createFlexMessage($row, $displayName);
        } else {
            error_log("No jobs found with status 1");
            $replymessage[] = [
                "type" => "text",
                "text" => "คุณไม่มีงานที่ต้องส่ง"
            ];
        }
    } else {
        error_log("Error executing SQL query: " . mysqli_error($conn));
        $replymessage[] = [
            "type" => "text",
            "text" => "Sorry, there was an error checking your jobs. Please try again later."
        ];
    }
}

function handleReceiveJob($reResult, &$replymessage) {
    global $conn;
    $replymessage[] = createReceiveJobFlexMessage($reResult);
}

function confirmComplete($postbackData, $replyToken, $userId) {
    global $conn;
    $responseId = $postbackData['itemId'];

    $selectQuery = "SELECT stretcher_register_id FROM stretcher_register WHERE stretcher_register_id = '$responseId'";
    $selectResult = mysqli_query($conn, $selectQuery);
    if ($selectResult) {
        $row = mysqli_fetch_assoc($selectResult);
        if ($row) {
            $updateQuery = "UPDATE stretcher_register SET stretcher_work_status_id = 2, lastupdate = NOW() WHERE stretcher_register_id = '$responseId'";

            try {
                $updateResult = mysqli_query($conn, $updateQuery);
            } catch (\Throwable $th) {
                error_log("Error updating status for stretcher_register_id '$responseId': " . $th->getMessage());
            }

            if ($updateResult) {
                $userProfile = getUserProfile($userId, ['AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
                $displayName = ensureNonEmpty($userProfile['displayName'], 'Unknown User');

                $replyMessage = [
                    [
                        "type" => "text",
                        "text" => "เยี่ยมมาก $displayName งานของคุณเสร็จเเล้ว"
                    ]
                ];

                $replyJson = json_encode([
                    "replyToken" => $replyToken,
                    "messages" => $replyMessage
                ]);

                sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
            } else {
                sendErrorMessage($replyToken, "Error updating status for stretcher_register_id '$responseId': " . mysqli_error($conn));
            }
        } else {
            sendErrorMessage($replyToken, "Item ID '$responseId' not found in the database.");
        }
    } else {
        sendErrorMessage($replyToken, "Error executing SQL query: " . mysqli_error($conn));
    }
}
?>
