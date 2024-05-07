<?php
$message = '{
    "line": {
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
                "type": "message",
                "text": "รับงาน",
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
              "text": "ด่วน"
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
                      "text": "สถานที่",
                      "size": "sm"
                    },
                    {
                      "size": "sm",
                      "text": "ห้องฉุกเฉิน - ห้องพัก1",
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
                      "text": "นางสาว บี",
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
                      "text": "เปลนอน"
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
    }
  }';
?>