<?php

namespace App\Http\Controllers;

use App\Services\LineApiService as LINEBot;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Constant\HTTPHeader;
use Illuminate\Http\Request;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LineWebhookController extends BaseController
{

    public function callback(Request $request)
    {
        if (!$request->headers->get(HTTPHeader::LINE_SIGNATURE)) {
            abort(400, 'line call back only.');
        }

        $events = $request->all()['events'];
        if (!$events) {
            // Line DeveloperのWebhook検証のため、通しておく
            abort(200);
        }

        foreach ($events as $event) {
            if (($event['type'] === 'follow')) {
                // 友達登録
                Log::debug('user follow');
            } else if (($event['type'] === 'unfollow')) {
                // 友達解除
                Log::debug('user unfollow');
            } else if ($event['type'] === 'message') {

                Log::debug($event);

                $httpClient = new CurlHTTPClient(config('services.line.message.channel_token'));
                $bot = new LINEBot($httpClient, ['channelSecret' => config('services.line.message.channel_secret')]);

                if (preg_match('/チャーシュー/u', $event['message']["text"])) {
                    $bot->sendTextMessage(env('DEMO_LINE_USER_ID'), "あいよー！");
                    $bot->sendImageMessage(env('DEMO_LINE_USER_ID'), "https://free-materials.com/adm/wp-content/uploads/2021/01/adpDSC_1712-.jpg");
                } else if (preg_match('/タンメン/u', $event['message']["text"])) {
                    $bot->sendTextMessage(env('DEMO_LINE_USER_ID'), "あいよー！");
                    $bot->sendImageMessage(env('DEMO_LINE_USER_ID'), "https://free-materials.com/adm/wp-content/uploads/2019/12/adpDSC_2194-1024x683.jpg");
                }
            }

        }

        return response()->json(['status' => 'ok']);
    }

    public function getProfile()
    {
        $httpClient = new CurlHTTPClient(config('services.line.message.channel_token'));
        $bot = new LINEBot($httpClient, ['channelSecret' => config('services.line.message.channel_secret')]);

        $response = $bot->getProfile(env('DEMO_LINE_USER_ID'));
        if ($response->isSucceeded()) {
            $profile = $response->getJSONDecodedBody();
            $params = [
                'display_name' => $profile['displayName'],
            ];

            if (isset($profile['pictureUrl'])) {
                $params['picture_url'] = $profile['pictureUrl'];
            }

            return response()->json($params);
        }

        if ($response->getHTTPStatus() === 404) {
            return response()->json(['errors' => 'line user is not found.'], 400);
        }

        Log::warning('getLineProfile: ' . $response->getJSONDecodedBody()['message']);
        return response()->json(['errors' => 'line bot api auth error.'], 400);
    }

    public function sendMessage()
    {
        $httpClient = new CurlHTTPClient(config('services.line.message.channel_token'));
        $bot = new LINEBot($httpClient, ['channelSecret' => config('services.line.message.channel_secret')]);

        $bot->sendTextMessage(env('DEMO_LINE_USER_ID'), "はい、てすとでーす。");
        return response()->json(['status' => 'ok']);
    }

    public function sendFlexMessage()
    {
        $httpClient = new CurlHTTPClient(config('services.line.message.channel_token'));
        $bot = new LINEBot($httpClient, ['channelSecret' => config('services.line.message.channel_secret')]);

        $bot->sendFlexMessage(env('DEMO_LINE_USER_ID'), $this->flexMessageAry);

        return response()->json(['status' => 'ok']);
    }

    public function sendImage()
    {
        $httpClient = new CurlHTTPClient(config('services.line.message.channel_token'));
        $bot = new LINEBot($httpClient, ['channelSecret' => config('services.line.message.channel_secret')]);

        $bot->sendImageMessage(env('DEMO_LINE_USER_ID'), "https://1.bp.blogspot.com/-j-izwRQMbHM/Xbo7ZXxilSI/AAAAAAABV2Q/J4DIT8lhTmgc5_Tja86oN63ffl0TFPaOwCNcBGAsYHQ/s1600/pose_goukaku6_schoolgirl.png");
        return response()->json(['status' => 'ok']);
    }


    public array $flexMessageAry = [
        "type" => "flex",
        "altText" => "message",
        "contents" => [
            "type" => "bubble",
            "hero" => [
                "type" => "image",
                "size" => "full",
                "aspectRatio" => "20:13",
                "aspectMode" => "cover",
                "action" => [
                    "type" => "uri",
                    "uri" => "http://linecorp.com/"
                ],
                "url" => "https://4.bp.blogspot.com/-0mGwb_xY7KQ/UgSMHVBsBMI/AAAAAAAAW8c/5PQpBk5sYVo/s800/food_ramen.png"
            ],
            "body" => [
                "type" => "box",
                "layout" => "vertical",
                "contents" => [
                    [
                        "type" => "text",
                        "text" => "ラーメン三郎",
                        "weight" => "bold",
                        "size" => "xl"
                    ],
                    [
                        "type" => "box",
                        "layout" => "baseline",
                        "margin" => "md",
                        "contents" => [
                            [
                                "type" => "icon",
                                "size" => "sm",
                                "url" => "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                            ],
                            [
                                "type" => "icon",
                                "size" => "sm",
                                "url" => "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                            ],
                            [
                                "type" => "icon",
                                "size" => "sm",
                                "url" => "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                            ],
                            [
                                "type" => "icon",
                                "size" => "sm",
                                "url" => "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                            ],
                            [
                                "type" => "icon",
                                "size" => "sm",
                                "url" => "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gray_star_28.png"
                            ],
                            [
                                "type" => "text",
                                "text" => "4.0",
                                "size" => "sm",
                                "color" => "#999999",
                                "margin" => "md",
                                "flex" => 0
                            ]
                        ]
                    ],
                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "margin" => "lg",
                        "spacing" => "sm",
                        "contents" => [
                            [
                                "type" => "box",
                                "layout" => "baseline",
                                "spacing" => "sm",
                                "contents" => [
                                    [
                                        "type" => "text",
                                        "text" => "Place",
                                        "color" => "#aaaaaa",
                                        "size" => "sm",
                                        "flex" => 1
                                    ],
                                    [
                                        "type" => "text",
                                        "text" => "Miraina Tower, 4-1-6 Shinjuku, Tokyo",
                                        "wrap" => true,
                                        "color" => "#666666",
                                        "size" => "sm",
                                        "flex" => 5
                                    ]
                                ]
                            ],
                            [
                                "type" => "box",
                                "layout" => "baseline",
                                "spacing" => "sm",
                                "contents" => [
                                    [
                                        "type" => "text",
                                        "text" => "Time",
                                        "color" => "#aaaaaa",
                                        "size" => "sm",
                                        "flex" => 1
                                    ],
                                    [
                                        "type" => "text",
                                        "text" => "10:00 - 23:00",
                                        "wrap" => true,
                                        "color" => "#666666",
                                        "size" => "sm",
                                        "flex" => 5
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "footer" => [
                "type" => "box",
                "layout" => "vertical",
                "spacing" => "sm",
                "contents" => [
                    [
                        "type" => "button",
                        "style" => "link",
                        "height" => "sm",
                        "action" => [
                            "type" => "uri",
                            "label" => "注文する",
                            "uri" => "https://linecorp.com"
                        ]
                    ],
                    [
                        "type" => "button",
                        "style" => "link",
                        "height" => "sm",
                        "action" => [
                            "type" => "uri",
                            "label" => "ホームページ",
                            "uri" => "https://linecorp.com"
                        ]
                    ],
                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "contents" => [
                        ],
                        "margin" => "sm"
                    ]
                ],
                "flex" => 0
            ]
        ]
    ];
}
