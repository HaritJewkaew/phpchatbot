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
    $userProfile = getUserProfile($userId, ['AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
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
                                      "text": "ผู้ป่วย",
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
  }
}


if (isset($postbackData['action']) && $postbackData['action'] == 'confirm_complete') {
  $responseId = $postbackData['itemId'];
  
  $selectQuery = "SELECT Info_id FROM request WHERE Info_id = '$responseId'";
  $selectResult = mysqli_query($conn, $selectQuery);
  if ($selectResult) {
      $row = mysqli_fetch_assoc($selectResult);
      if ($row) {
          $updateQuery = "UPDATE request SET สถานะ = 2, เวลา = NOW() WHERE Info_id = '$responseId'";
          
          try {
              $updateResult = mysqli_query($conn, $updateQuery);
          } catch (\Throwable $th) {
              error_log("Error updating status for Info_id '$responseId': " . $th->getMessage());
          }
          
          if ($updateResult) {
              // Send acknowledgment to the user
              $replyMessage = [
                  [
                      "type" => "text",
                      "text" =>"เยี่ยมมากงานของคุณเสร็จเเล้ว $displayName"
                  ]
              ];

              $replyJson = json_encode([
                  "replyToken" => $replyToken,
                  "messages" => $replyMessage
              ]);

              sendMessage($replyJson, ['URL' => "https://api.line.me/v2/bot/message/reply", 'AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
          } else {
              // Handle error if the update fails
              error_log("Error updating status for Info_id '$responseId': " . mysqli_error($conn));
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
          // Handle if the item ID is not found
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
$userId = isset($jsonData['events'][0]['source']['userId']) ? $jsonData['events'][0]['source']['userId'] : null;
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
  // case 'ส่งงาน':
  //   $replymessage[] = json_decode('{
  //     "type": "flex",
  //     "altText": "Flex Message",
  //     "contents": {
  //       "type": "bubble",
  //       "footer": {
  //         "type": "box",
  //         "spacing": "sm",
  //         "layout": "vertical",
  //         "contents": [
  //           {
  //             "color": "#0077FF",
  //             "style": "primary",
  //             "height": "sm",
  //             "type": "button",
  //             "action": {
  //               "type": "postback",
  //               "data": "action=accept_job&itemId=' . $reResult['ID'] . '",
  //               "label": "ยืนยันส่งงาน"
  //             }
  //           }
  //         ]
  //       },
  //       "body": {
  //         "layout": "vertical",
  //         "type": "box",
  //         "contents": [
  //           {
  //             "weight": "bold",
  //             "type": "text",
  //             "size": "xl",
  //             "text": "' . $reResult['status'] . '"
  //           },
  //           {
  //             "contents": [
  //               {
  //                 "type": "box",
  //                 "spacing": "sm",
  //                 "layout": "baseline",
  //                 "contents": [
  //                   {
  //                     "color": "#aaaaaa",
  //                     "type": "text",
  //                     "text": "ID",
  //                     "size": "sm"
  //                   },
  //                   {
  //                     "size": "sm",
  //                     "text": "' . $reResult['ID'] . '",
  //                     "wrap": true,
  //                     "color": "#666666",
  //                     "type": "text"
  //                   }
  //                 ]
  //               },
  //               {
  //                 "spacing": "sm",
  //                 "type": "box",
  //                 "layout": "baseline",
  //                 "contents": [
  //                   {
  //                     "text": "ผู้เรียก",
  //                     "size": "sm",
  //                     "color": "#aaaaaa",
  //                     "type": "text"
  //                   },
  //                   {
  //                     "size": "sm",
  //                     "text": "' . $reResult['Caller'] . '",
  //                     "color": "#666666",
  //                     "wrap": true,
  //                     "type": "text"
  //                   }
  //                 ]
  //               },
  //               {
  //                 "spacing": "sm",
  //                 "type": "box",
  //                 "layout": "baseline",
  //                 "contents": [
  //                   {
  //                     "text": "ผู้ป่วย",
  //                     "size": "sm",
  //                     "color": "#aaaaaa",
  //                     "type": "text"
  //                   },
  //                   {
  //                     "size": "sm",
  //                     "text": "' . $reResult['Patient'] . '",
  //                     "color": "#666666",
  //                     "wrap": true,
  //                     "type": "text"
  //                   }
  //                 ]
  //               },
  //               {
  //                 "type": "box",
  //                 "spacing": "sm",
  //                 "layout": "baseline",
  //                 "contents": [
  //                   {
  //                     "color": "#aaaaaa",
  //                     "type": "text",
  //                     "text": "สถานที่รับ",
  //                     "size": "sm"
  //                   },
  //                   {
  //                     "size": "sm",
  //                     "text": "' . $reResult['location'] . '",
  //                     "wrap": true,
  //                     "color": "#666666",
  //                     "type": "text"
  //                   }
  //                 ]
  //               },
  //               {
  //                 "type": "box",
  //                 "spacing": "sm",
  //                 "layout": "baseline",
  //                 "contents": [
  //                   {
  //                     "color": "#aaaaaa",
  //                     "type": "text",
  //                     "text": "สถานที่ส่ง",
  //                     "size": "sm"
  //                   },
  //                   {
  //                     "size": "sm",
  //                     "text": "' . $reResult['locations'] . '",
  //                     "wrap": true,
  //                     "color": "#666666",
  //                     "type": "text"
  //                   }
  //                 ]
  //               },
  //               {
  //                 "layout": "baseline",
  //                 "contents": [
  //                   {
  //                     "text": "ประเภทเปล",
  //                     "type": "text",
  //                     "size": "sm",
  //                     "color": "#aaaaaa"
  //                   },
  //                   {
  //                     "size": "sm",
  //                     "type": "text",
  //                     "wrap": true,
  //                     "text": "' . $reResult['Type'] . '"
  //                   }
  //                 ],
  //                 "type": "box"
  //               }
  //             ],
  //             "type": "box",
  //             "margin": "lg",
  //             "spacing": "sm",
  //             "layout": "vertical"
  //           }
  //         ]
  //       }
  //     }
  //   }', true);
  // break;

  case 'ส่งงาน':
    // Fetch user profile if userId is available
    $userId = isset($jsonData['events'][0]['source']['userId']) ? $jsonData['events'][0]['source']['userId'] : null;
    if ($userId) {
        $userProfile = getUserProfile($userId, ['AccessToken' => 'OFvAmyeycV9atKHD7us21lzfwsG3NJGFMXTRc+cpWwY1EiKknhBihm7CW7rMjoOExw/7w0iT6CwRwrFW7pXGZ296IuylEbnVKcTzPXCcjyFpEn4X1QeTYvVEoUT9xAVRwQjliEEoP4whuGoGBoMLbAdB04t89/1O/w1cDnyilFU=']);
        $displayName = $userProfile['displayName'] ?? 'Unknown User';
        error_log("Fetched user profile: " . json_encode($userProfile));
    } else {
        $displayName = 'Unknown User';
        error_log("User ID not found");
    }

    // Log the displayName for debugging
    error_log("Display Name: $displayName");

    $checkQuery = "SELECT * FROM request WHERE ผู้รับ = '$displayName' AND สถานะ = 1 LIMIT 1";
    
    // Log the SQL query for debugging
    error_log("SQL Query: $checkQuery");

    $checkResult = mysqli_query($conn, $checkQuery);
    if ($checkResult) {
        $row = mysqli_fetch_assoc($checkResult);
        if ($row) {
            $infoId = $row['Info_id'];
            error_log("Found job: " . json_encode($row));

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
                                    "data": "action=confirm_complete&itemId=' . $row['Info_id'] . '",
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
                                "text": "' . $row['ความเร่งด่วน'] . '"
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
                                                "text": "' . $row['Info_id'] . '",
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
                                                "text": "' . $row['ผู้เรียก'] . '",
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
                                                "text": "ผู้ป่วย",
                                                "size": "sm",
                                                "color": "#aaaaaa",
                                                "type": "text"
                                            },
                                            {
                                                "size": "sm",
                                                "text": "' . $row['ชื่อผู้ป่วย'] . '",
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
                                                "text": "' . $row['สถานที่รับ'] . '",
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
                                                "text": "' . $row['สถานที่ส่ง'] . '",
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
                                                "text": "' . $row['ประเภทเปล'] . '"
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
        } else {
            error_log("No jobs found with status 1");
            $replymessage[] = [
                "type" => "text",
                "text" => "You have no jobs with status 1."
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
                        "text": "ผู้ป่วย",
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