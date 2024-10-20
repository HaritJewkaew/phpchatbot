<?php
include("query.php");

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

function ensureNonEmpty($value, $default = "ไม่ระบุ") {
    return !empty($value) ? $value : $default;
}

$reResult = array_map('ensureNonEmpty', $reResult);

if (isset($jsonData["events"][0]["type"]) && $jsonData["events"][0]["type"] == "postback") {
    parse_str($jsonData["events"][0]["postback"]["data"], $postbackData);
    if (isset($postbackData['action']) && $postbackData['action'] == 'accept_job') {
        $itemId = $postbackData['itemId'];
        $userProfile = getUserProfile($userId, ['AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
        $displayName = ensureNonEmpty($userProfile['displayName'], 'Unknown User');
        $selectQuery = "SELECT stretcher_register_id FROM stretcher_register WHERE stretcher_register_id = '$itemId'";
        $selectResult = mysqli_query($conn, $selectQuery);
        if ($selectResult) {
            $row = mysqli_fetch_assoc($selectResult);
            if ($row) {
                $infoId = $row['stretcher_register_id'];
                $updateQuery = "UPDATE stretcher_register SET stretcher_work_status_id = 2, ผู้รับ = '$displayName', stretcher_register_send_time = curtime() , stretcher_register_accept_date = curdate() WHERE stretcher_register_id = '$infoId'";

                try {
                    $updateResult = mysqli_query($conn, $updateQuery);
                } catch (\Throwable $th) {
                    error_log($th->getMessage());
                }

                if ($updateResult) {
                    $responemessage[] = json_decode('{
                        "type": "flex",
                        "altText": "Flex Message",
                        "contents": {
                            "type": "bubble",
                            "hero": {
                                "size": "4xl",
                                "action": {
                                    "type": "uri",
                                    "uri": "http://linecorp.com/"
                                },
                                "url": "https://img.freepik.com/premium-vector/continuous-one-line-drawing-medical-team-resuscitates-affected-person-doctor-take-patient-gurney_533993-27953.jpg?w=996",
                                "type": "image"
                            },
                            "body": {
                                "layout": "vertical",
                                "type": "box",
                                "contents": [
                                    {
                                        "contents": [
                                            {
                                                "type": "box",
                                                "spacing": "sm",
                                                "layout": "baseline",
                                                "contents": [
                                                    {
                                                        "color": "#aaaaaa",
                                                        "type": "text",
                                                        "text": "ID",
                                                        "size": "sm"
                                                    },
                                                    {
                                                        "size": "sm",
                                                        "text": "' . $reResult['ID'] . '",
                                                        "wrap": true,
                                                        "color": "#666666",
                                                        "type": "text"
                                                    }
                                                ]
                                            },
                                            {
                                                "spacing": "sm",
                                                "type": "box",
                                                "layout": "baseline",
                                                "contents": [
                                                    {
                                                        "text": "ผู้เรียก",
                                                        "size": "sm",
                                                        "color": "#aaaaaa",
                                                        "type": "text"
                                                    },
                                                    {
                                                        "size": "sm",
                                                        "text": "' . $reResult['Caller'] . '",
                                                        "color": "#666666",
                                                        "wrap": true,
                                                        "type": "text"
                                                    }
                                                ]
                                            },
                                            {
                                                "spacing": "sm",
                                                "type": "box",
                                                "layout": "baseline",
                                                "contents": [
                                                    {
                                                        "text": "HN",
                                                        "size": "sm",
                                                        "color": "#aaaaaa",
                                                        "type": "text"
                                                    },
                                                    {
                                                        "size": "sm",
                                                        "text": "' . $reResult['Patient'] . '",
                                                        "color": "#666666",
                                                        "wrap": true,
                                                        "type": "text"
                                                    }
                                                ]
                                            },
                                            {
                                                "type": "box",
                                                "spacing": "sm",
                                                "layout": "baseline",
                                                "contents": [
                                                    {
                                                        "color": "#aaaaaa",
                                                        "type": "text",
                                                        "text": "สถานที่รับ",
                                                        "size": "sm"
                                                    },
                                                    {
                                                        "size": "sm",
                                                        "text": "' . $reResult['location'] . '",
                                                        "wrap": true,
                                                        "color": "#666666",
                                                        "type": "text"
                                                    }
                                                ]
                                            },
                                            {
                                                "type": "box",
                                                "spacing": "sm",
                                                "layout": "baseline",
                                                "contents": [
                                                    {
                                                        "color": "#aaaaaa",
                                                        "type": "text",
                                                        "text": "สถานที่ส่ง",
                                                        "size": "sm"
                                                    },
                                                    {
                                                        "size": "sm",
                                                        "text": "' . $reResult['locations'] . '",
                                                        "wrap": true,
                                                        "color": "#666666",
                                                        "type": "text"
                                                    }
                                                ]
                                            },
                                            {
                                                "layout": "baseline",
                                                "contents": [
                                                    {
                                                        "text": "ประเภทเปล",
                                                        "type": "text",
                                                        "size": "sm",
                                                        "color": "#aaaaaa"
                                                    },
                                                    {
                                                        "size": "sm",
                                                        "type": "text",
                                                        "wrap": true,
                                                        "text": "' . $reResult['Type'] . '"
                                                    }
                                                ],
                                                "type": "box"
                                            },
                                            {
                                                "layout": "baseline",
                                                "contents": [
                                                    {
                                                        "text": "ผู้รับงาน",
                                                        "type": "text",
                                                        "size": "sm",
                                                        "color": "#aaaaaa"
                                                    },
                                                    {
                                                        "size": "sm",
                                                        "type": "text",
                                                        "wrap": true,
                                                        "text": "' . $displayName . '"
                                                    }
                                                ],
                                                "type": "box"
                                            }
                                        ],
                                        "type": "box",
                                        "margin": "lg",
                                        "spacing": "sm",
                                        "layout": "vertical"
                                    }
                                ]
                            }
                        }
                    }', true);

                    $responseJson = json_encode([
                        "replyToken" => $replyToken,
                        "messages" => $responemessage
                    ]);
                    sendMessage($responseJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
                } else {
                    error_log("Error updating status for stretcher_register_id '$infoId': " . mysqli_error($conn));
                    $replyMessage = [
                        [
                            "type" => "text",
                            "text" => "Sorry, I couldn't update the status. Please try again later."
                        ]
                    ];

                    $replyJson = json_encode([
                        "replyToken" => $replyToken,
                        "messages" => $replyMessage
                    ]);

                    sendMessage($replyJson, $lineData);
                }
            } else {
                error_log("Item ID '$itemId' not found in the database.");
                $replyMessage = [
                    [
                        "type" => "text",
                        "text" => "Sorry, I couldn't update the status. Please try again later."
                    ]
                ];

                $replyJson = json_encode([
                    "replyToken" => $replyToken,
                    "messages" => $replyMessage
                ]);

                sendMessage($replyJson, $lineData);
            }
        } else {
            error_log("Error executing SQL query: " . mysqli_error($conn));
            $replyMessage = [
                [
                    "type" => "text",
                    "text" => "Sorry, I couldn't update the status. Please try again later."
                ]
            ];

            $replyJson = json_encode([
                "replyToken" => $replyToken,
                "messages" => $replyMessage
            ]);

            sendMessage($replyJson, $lineData);
        }
    }
}

if (isset($postbackData['action']) && $postbackData['action'] == 'confirm_complete') {
    $responseId = $postbackData['itemId'];

    $selectQuery = "SELECT stretcher_register_id FROM stretcher_register WHERE stretcher_register_id = '$responseId'";
    $selectResult = mysqli_query($conn, $selectQuery);
    if ($selectResult) {
        $row = mysqli_fetch_assoc($selectResult);
        if ($row) {
            $updateQuery = "UPDATE stretcher_register SET stretcher_work_status_id = 3, lastupdate = NOW(), stretcher_register_return_time = curtime() WHERE stretcher_register_id = '$responseId'";

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
                        "text" => "เยี่ยมมาก งานของคุณเสร็จเเล้ว"
                    ]
                ];

                $replyJson = json_encode([
                    "replyToken" => $replyToken,
                    "messages" => $replyMessage
                ]);

                sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
            } else {
                error_log("Error updating status for stretcher_register_id '$responseId': " . mysqli_error($conn));
                $replyMessage = [
                    [
                        "type" => "text",
                        "text" => "Sorry, I couldn't update the status. Please try again later."
                    ]
                ];

                $replyJson = json_encode([
                    "replyToken" => $replyToken,
                    "messages" => $replyMessage
                ]);

                sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
            }
        } else {
            error_log("Item ID '$responseId' not found in the database.");
            $replyMessage = [
                [
                    "type" => "text",
                    "text" => "Sorry, the item ID is not found in the database."
                ]
            ];

            $replyJson = json_encode([
                "replyToken" => $replyToken,
                "messages" => $replyMessage
            ]);

            sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
        }
    } else {
        error_log("Error executing SQL query: " . mysqli_error($conn));
        $replyMessage = [
            [
                "type" => "text",
                "text" => "Sorry, there was an error processing your request. Please try again later."
            ]
        ];

        $replyJson = json_encode([
            "replyToken" => $replyToken,
            "messages" => $replyMessage
        ]);

        sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
    }
}

function sendMessage($replyJson, $token)
{
    $datasReturn = [];
    $curl = curl_init();
    if ($curl === false) {
        error_log('Failed to initialize cURL session');
        return ['result' => 'E', 'message' => 'cURL initialization failed'];
    }

    curl_setopt_array(
        $curl,
        array(
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
        )
    );

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

function getUserProfile($userId, $token)
{
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => "https://api.line.me/v2/bot/profile/" . $userId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token['AccessToken'],
            ),
        )
    );
    $response = curl_exec($curl);
    if ($response === false) {
        error_log('Failed to fetch user profile: ' . curl_error($curl));
        curl_close($curl);
        return null;
    }
    curl_close($curl);
    return json_decode($response, true);
}

$replymessage = [];
switch ($text) {

    case 'เช็คงาน':
        $id = $reResult['ID'];
        $checkQuery = "SELECT count(stretcher_register_id) as QTY FROM stretcher_register WHERE stretcher_work_status_id = 1;";
        error_log("SQL Query: $checkQuery");
        
        $checkResult = mysqli_query($conn, $checkQuery);
        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);
            if ($row) {
                $infoId = $row['QTY'];
                error_log("Found job: " . json_encode($row));
            }
            if ($row['QTY']=="0") {
                $replyMessage = [
                    [
                        "type" => "text",
                        "text" => "ไม่เหลืองานค้างอยู่ในระบบ"
                    ]
                ];
            }else {
                $replyMessage = [
                    [
                        "type" => "text",
                        "text" => "งานในระบบที่เหลืออยู่มี " . $row['QTY'] . " งาน"
                    ]
                ];
            }
           
            $replyJson = json_encode([
                "replyToken" => $replyToken,
                "messages" => $replyMessage
            ]);

            sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);

        break;
    }

    case 'ส่งงาน':
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
        
        
        $checkQuery = "select sr.stretcher_register_id,sr.hn,sdep.department as 'sent_dep',
                fdep.department as 'from_dep',st.stretcher_type_name ,sr.doctor_request,
                srs.R_name as 'receiver' 
                from stretcher_register sr 
                left join kskdepartment sdep ON sr.send_depcode=sdep.depcode
                left join kskdepartment fdep ON sr.from_depcode=fdep.depcode
                left join stretcher_type st ON sr.stretcher_type_id=st.stretcher_type_id
                left join stretcher_request_staff srs ON sr.ผู้รับ=srs.Line_name
                WHERE ผู้รับ = '$displayName' AND stretcher_work_status_id = 2";
                                                                                                   
        error_log("SQL Query: $checkQuery");
                                                                                                   
        $checkResult = mysqli_query($conn, $checkQuery);
        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);
            if ($row) {
                $infoId = $row['stretcher_register_id'];
                error_log("Found job: " . json_encode($row));

                $replymessage[] = json_decode('{
                    "type": "flex",
                    "altText": "Flex Message",
                    "contents": {
                        "type": "bubble",
                        "hero": {
                                "size": "4xl",
                                "action": {
                                    "type": "uri",
                                    "uri": "http://linecorp.com/"
                                },
                                "url": "https://img.freepik.com/premium-vector/continuous-one-line-drawing-medical-team-resuscitates-affected-person-doctor-take-patient-gurney_533993-27953.jpg?w=996",
                                "type": "image"
                            },

                        "footer": {
                            "type": "box",
                            "spacing": "sm",
                            "layout": "vertical",
                            "contents": [
                                {
                                    "color": "#00A36C",
                                    "style": "primary",
                                    "height": "sm",
                                    "type": "button",
                                    "action": {
                                        "type": "postback",
                                        "data": "action=confirm_complete&itemId=' . $row['stretcher_register_id'] . '",
                                        "label": "ยืนยันส่งงาน"
                                    }
                                }
                            ]
                        },
                        "body": {
                            "layout": "vertical",
                            "type": "box",
                            "contents": [
                                {
                                    "weight": "bold",
                                    "type": "text",
                                    "size": "xl",
                                    "text": "ส่งงาน"
                                },
                                {
                                    "contents": [
                                        {
                                            "type": "box",
                                            "spacing": "sm",
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "color": "#aaaaaa",
                                                    "type": "text",
                                                    "text": "ID",
                                                    "size": "sm"
                                                },
                                                {
                                                    "size": "sm",
                                                    "text": "' . ensureNonEmpty($row['stretcher_register_id']) . '",
                                                    "wrap": true,
                                                    "color": "#666666",
                                                    "type": "text"
                                                }
                                            ]
                                        },
                                        {
                                            "spacing": "sm",
                                            "type": "box",
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "text": "ผู้เรียก",
                                                    "size": "sm",
                                                    "color": "#aaaaaa",
                                                    "type": "text"
                                                },
                                                {
                                                    "size": "sm",
                                                    "text": "' . ensureNonEmpty($row['doctor_request']) . '",
                                                    "color": "#666666",
                                                    "wrap": true,
                                                    "type": "text"
                                                }
                                            ]
                                        },
                                        {
                                            "spacing": "sm",
                                            "type": "box",
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "text": "HN",
                                                    "size": "sm",
                                                    "color": "#aaaaaa",
                                                    "type": "text"
                                                },
                                                {
                                                    "size": "sm",
                                                    "text": "' . ensureNonEmpty($row['hn']) . '",
                                                    "color": "#666666",
                                                    "wrap": true,
                                                    "type": "text"
                                                }
                                            ]
                                        },
                                        {
                                            "type": "box",
                                            "spacing": "sm",
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "color": "#aaaaaa",
                                                    "type": "text",
                                                    "text": "สถานที่รับ",
                                                    "size": "sm"
                                                },
                                                {
                                                    "size": "sm",
                                                    "text": "' . $row['from_dep'] . '",
                                                    "wrap": true,
                                                    "color": "#666666",
                                                    "type": "text"
                                                }
                                            ]
                                        },
                                        {
                                            "type": "box",
                                            "spacing": "sm",
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "color": "#aaaaaa",
                                                    "type": "text",
                                                    "text": "สถานที่ส่ง",
                                                    "size": "sm"
                                                },
                                                {
                                                    "size": "sm",
                                                    "text": "' . $row['sent_dep'] . '",
                                                    "wrap": true,
                                                    "color": "#666666",
                                                    "type": "text"
                                                }
                                            ]
                                        },
                                        {
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "text": "ประเภทเปล",
                                                    "type": "text",
                                                    "size": "sm",
                                                    "color": "#aaaaaa"
                                                },
                                                {
                                                    "size": "sm",
                                                    "type": "text",
                                                    "wrap": true,
                                                    "text": "' . $row['stretcher_type_name'] . '"
                                                }
                                            ],
                                            "type": "box"
                                        },
                                        {
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "text": "ผู้ส่งงาน",
                                                    "type": "text",
                                                    "size": "sm",
                                                    "color": "#aaaaaa"
                                                },
                                                {
                                                    "size": "sm",
                                                    "type": "text",
                                                    "wrap": true,
                                                    "text": "' . $row['receiver'] . '"
                                                }
                                            ],
                                            "type": "box"
                                        }
                                    ],
                                    "type": "box",
                                    "margin": "lg",
                                    "spacing": "sm",
                                    "layout": "vertical"
                                }
                            ]
                        }
                    }
                }', true);
            } else {
                error_log("No jobs found with status 1");
                $replymessage[] = [
                    "type" => "text",
                    "text" => "You have no jobs with status 1. ID = $idreg"
                ];
            }
        } else {
            error_log("Error executing SQL query: " . mysqli_error($conn));
            $replymessage[] = [
                "type" => "text",
                "text" => "Sorry, there was an error checking your jobs. Please try again later."
            ];
        }
        break;

    case 'รับงาน' || 'r':
        
        $replymessage[] = json_decode('{
            "type": "flex",
            "altText": "Flex Message",
            "contents": {
                "type": "bubble",
                "footer": {
                    "type": "box",
                    "spacing": "sm",
                    "layout": "vertical",
                    "contents": [
                        {
                            "color": "#2fb7e7",
                            "style": "primary",
                            "height": "sm",
                            "type": "button",
                            "action": {
                                "type": "postback",
                                "data": "action=accept_job&itemId=' . $reResult['ID'] . '",
                                "label": "รับงาน"
                            }
                        }
                    ]
                },
                "hero": {
                    "size": "4xl",
                    "action": {
                        "type": "uri",
                        "uri": "http://linecorp.com/"
                    },
                    "url": "https://img.freepik.com/premium-vector/continuous-one-line-drawing-medical-team-resuscitates-affected-person-doctor-take-patient-gurney_533993-27953.jpg?w=996",
                    "type": "image"
                },
                "body": {
                    "layout": "vertical",
                    "type": "box",
                    "contents": [
                        {
                            "contents": [
                                {
                                    "type": "box",
                                    "spacing": "sm",
                                    "layout": "baseline",
                                    "contents": [
                                        {
                                            "color": "#aaaaaa",
                                            "type": "text",
                                            "text": "ID",
                                            "size": "sm"
                                        },
                                        {
                                            "size": "sm",
                                            "text": "' . $reResult['ID'] . '",
                                            "wrap": true,
                                            "color": "#666666",
                                            "type": "text"
                                        }
                                    ]
                                },
                                {
                                    "spacing": "sm",
                                    "type": "box",
                                    "layout": "baseline",
                                    "contents": [
                                        {
                                            "text": "ผู้เรียก",
                                            "size": "sm",
                                            "color": "#aaaaaa",
                                            "type": "text"
                                        },
                                        {
                                            "size": "sm",
                                            "text": "' . $reResult['Caller'] . '",
                                            "color": "#666666",
                                            "wrap": true,
                                            "type": "text"
                                        }
                                    ]
                                },
                                {
                                    "spacing": "sm",
                                    "type": "box",
                                    "layout": "baseline",
                                    "contents": [
                                        {
                                            "text": "HN",
                                            "size": "sm",
                                            "color": "#aaaaaa",
                                            "type": "text"
                                        },
                                        {
                                            "size": "sm",
                                            "text": "' . $reResult['Patient'] . '",
                                            "color": "#666666",
                                            "wrap": true,
                                            "type": "text"
                                        }
                                    ]
                                },
                                {
                                    "type": "box",
                                    "spacing": "sm",
                                    "layout": "baseline",
                                    "contents": [
                                        {
                                            "color": "#aaaaaa",
                                            "type": "text",
                                            "text": "สถานที่รับ",
                                            "size": "sm"
                                        },
                                        {
                                            "size": "sm",
                                            "text": "' . $reResult['location'] . '",
                                            "wrap": true,
                                            "color": "#666666",
                                            "type": "text"
                                        }
                                    ]
                                },
                                {
                                    "type": "box",
                                    "spacing": "sm",
                                    "layout": "baseline",
                                    "contents": [
                                        {
                                            "color": "#aaaaaa",
                                            "type": "text",
                                            "text": "สถานที่ส่ง",
                                            "size": "sm"
                                        },
                                        {
                                            "size": "sm",
                                            "text": "' . $reResult['locations'] . '",
                                            "wrap": true,
                                            "color": "#666666",
                                            "type": "text"
                                        }
                                    ]
                                },
                                {
                                    "layout": "baseline",
                                    "contents": [
                                        {
                                            "text": "ประเภทเปล",
                                            "type": "text",
                                            "size": "sm",
                                            "color": "#aaaaaa"
                                        },
                                        {
                                            "size": "sm",
                                            "type": "text",
                                            "wrap": true,
                                            "text": "' . $reResult['Type'] . '"
                                        }
                                    ],
                                    "type": "box"
                                }
                            ],
                            "type": "box",
                            "margin": "lg",
                            "spacing": "sm",
                            "layout": "vertical"
                        }
                    ]
                }
            }
        }', true);
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

$results = sendMessage($replyJson, $lineData);
http_response_code(200);
?>
