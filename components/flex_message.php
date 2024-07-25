<?php
function createFlexMessage($row, $displayName) {
    return json_decode('{
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
                        "text": "' . ensureNonEmpty($row['stretcher_type_id']) . '"
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
                                        "text": "ผู้ป่วย",
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
                                        "text": "' . ensureNonEmpty($row['from_note']) . '",
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
                                        "text": "' . ensureNonEmpty($row['send_note']) . '",
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
                                        "text": "' . ensureNonEmpty($row['stretcher_type_id']) . '"
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
}

function createReceiveJobFlexMessage($reResult) {
    return json_decode('{
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
}
?>
