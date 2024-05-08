<?php
include ("query.php");
$LINEData = file_get_contents('php://input');
file_put_contents('log.txt', $LINEData . PHP_EOL, FILE_APPEND);

$jsonData = json_decode($LINEData, true);
$reResult = getRequestResult();

if (!empty($jsonData["events"][0]["replyToken"])) {
  $replyToken = $jsonData["events"][0]["replyToken"];
} else {
  error_log("No replyToken found.");
  return;
}

if (!empty($jsonData["events"][0]["message"]["text"])) {
  $text = strtolower($jsonData["events"][0]["message"]["text"]);
} else {
  $text = "";
}

$userId = isset($jsonData['events'][0]['source']['userId']) ? $jsonData['events'][0]['source']['userId'] : null;
$userProfile = null;
$utype = isset($jsonData['events'][0]['type']) ? $jsonData['events'][0]['type'] : null;

$infoId = null;
if (isset($jsonData["events"][0]["type"]) && $jsonData["events"][0]["type"] == "postback") {
  parse_str($jsonData["events"][0]["postback"]["data"], $postbackData);
  if (isset($postbackData['action']) && $postbackData['action'] == 'accept_job') {
    // Fetch user profile if needed
    $itemId = $postbackData['itemId'];
    $userProfile = getUserProfile($userId, ['AccessToken' => 'F+ljw+7TZDZyPOrXtDnQD9Xp9gkPpb/Kr4qlcdJvmYCx/C9j2qeIKRIp4QbIWS1p13JlyHVS3HT3JZjUg/gMC+jQkwEpHtL/Rz5nSZ+KvedPTYgAd/fG9RMq4P0QUfWBybvS3hApK6ub2TKN4CQb0gdB04t89/1O/w1cDnyilFU=']);
    $displayName = $userProfile['displayName'] ?? 'Unknown User';
    $selectQuery = "SELECT Info_id FROM request WHERE Info_id = '$itemId'";
    $selectResult = mysqli_query($conn, $selectQuery);
    if ($selectResult) {
      $row = mysqli_fetch_assoc($selectResult);
      if ($row) {
        $infoId = $row['Info_id'];
        $updateQuery = "UPDATE request SET สถานะ = 1 WHERE Info_id = '$infoId'";
        $updateReQuery = "UPDATE request SET ผู้รับ = '$displayName' WHERE Info_id = '$infoId'";
      
        try {
          $updateResult = mysqli_query($conn, $updateQuery);
          $updateReName = mysqli_query($conn, $updateReQuery);
        } catch (\Throwable $th) {
          log("" . $th->getMessage());
        }
        //$updateResult = mysqli_query($conn, $updateQuery);
        if ($updateResult) {

        } else {

          error_log("Error updating status for Info_id '$infoId': " . mysqli_error($conn));
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
    // Acknowledge the acceptance
    $responemessage[] = json_decode('{
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
              "color": "#0077FF",
              "style": "primary",
              "height": "sm",
              "type": "button",
              "action": {
                "type": "postback",
                "data": "action=job_completed&requestId=' . $itemId . '",
                "label": "ทำงานเสร็จแล้ว"
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
          "url": "https://freeiconshop.com/wp-content/uploads/edd/task-done-outline-filled.png",
          "type": "image"
        }
      }
    }', true);

    $responseJson = json_encode([
      "replyToken" => $replyToken,
      "messages" => $responemessage
    ]);
    sendMessage($responseJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'F+ljw+7TZDZyPOrXtDnQD9Xp9gkPpb/Kr4qlcdJvmYCx/C9j2qeIKRIp4QbIWS1p13JlyHVS3HT3JZjUg/gMC+jQkwEpHtL/Rz5nSZ+KvedPTYgAd/fG9RMq4P0QUfWBybvS3hApK6ub2TKN4CQb0gdB04t89/1O/w1cDnyilFU=']);
  }
}


if (isset($postbackData['action']) && $postbackData['action'] == 'job_completed') {
  // Fetch user profile if needed
  $responseId = $postbackData['requestId'];
  
  
  $selectQuery = "SELECT Info_id FROM request WHERE Info_id = '$responseId'";
  $selectResult = mysqli_query($conn, $selectQuery);
  if ($selectResult) {
      $row = mysqli_fetch_assoc($selectResult);
      if ($row) {
          $updateQuery = "UPDATE request SET เวลา = NOW() WHERE Info_id = '$responseId'";
          
      
          try {
              $updateResult = mysqli_query($conn, $updateQuery);
             
          } catch (\Throwable $th) {
              log("" . $th->getMessage());
          }
          
          if ($updateResult) {
              // Send acknowledgment to the user
              $replyMessage = [
                  [
                      "type" => "text",
                      "text" => "Job marked as complete successfully."
                  ]
              ];

              $replyJson = json_encode([
                  "replyToken" => $replyToken,
                  "messages" => $replyMessage
              ]);

              sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'F+ljw+7TZDZyPOrXtDnQD9Xp9gkPpb/Kr4qlcdJvmYCx/C9j2qeIKRIp4QbIWS1p13JlyHVS3HT3JZjUg/gMC+jQkwEpHtL/Rz5nSZ+KvedPTYgAd/fG9RMq4P0QUfWBybvS3hApK6ub2TKN4CQb0gdB04t89/1O/w1cDnyilFU=']);
          } else {
              // Handle error if the update fails
              error_log("Error updating status for Info_id '$infoId': " . mysqli_error($conn));
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

              sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'F+ljw+7TZDZyPOrXtDnQD9Xp9gkPpb/Kr4qlcdJvmYCx/C9j2qeIKRIp4QbIWS1p13JlyHVS3HT3JZjUg/gMC+jQkwEpHtL/Rz5nSZ+KvedPTYgAd/fG9RMq4P0QUfWBybvS3hApK6ub2TKN4CQb0gdB04t89/1O/w1cDnyilFU=']);
          }
      } else {
          // Handle if the item ID is not found
          error_log("Item ID '$itemId' not found in the database.");
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

          sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'F+ljw+7TZDZyPOrXtDnQD9Xp9gkPpb/Kr4qlcdJvmYCx/C9j2qeIKRIp4QbIWS1p13JlyHVS3HT3JZjUg/gMC+jQkwEpHtL/Rz5nSZ+KvedPTYgAd/fG9RMq4P0QUfWBybvS3hApK6ub2TKN4CQb0gdB04t89/1O/w1cDnyilFU=']);
      }
  } else {
      // Handle database query error
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

      sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'F+ljw+7TZDZyPOrXtDnQD9Xp9gkPpb/Kr4qlcdJvmYCx/C9j2qeIKRIp4QbIWS1p13JlyHVS3HT3JZjUg/gMC+jQkwEpHtL/Rz5nSZ+KvedPTYgAd/fG9RMq4P0QUfWBybvS3hApK6ub2TKN4CQb0gdB04t89/1O/w1cDnyilFU=']);
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
  error_log("API response: $result"); // Log the response from the API

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
  case 'รับงาน':
    $replymessage[] = [
      "type" => "text",
      "text" => "Hello, this is a reply from case 'a'!"
    ];
    break;
  case 'request' || 'r':
    // Add your Flex message JSON structure here
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
                "color": "#0077FF",
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
            "url": "https://www.trueplookpanya.com/data/product/uploads/other4/exclamat_orange.jpg",
            "type": "image"
          },
          "body": {
            "layout": "vertical",
            "type": "box",
            "contents": [
              {
                "weight": "bold",
                "type": "text",
                "size": "xl",
                "text": "' . $reResult['status'] . '"
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
                        "text": "' . $reResult['ID'] . '",
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
                        "text": "สถานที่",
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
  'AccessToken' => "F+ljw+7TZDZyPOrXtDnQD9Xp9gkPpb/Kr4qlcdJvmYCx/C9j2qeIKRIp4QbIWS1p13JlyHVS3HT3JZjUg/gMC+jQkwEpHtL/Rz5nSZ+KvedPTYgAd/fG9RMq4P0QUfWBybvS3hApK6ub2TKN4CQb0gdB04t89/1O/w1cDnyilFU="
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