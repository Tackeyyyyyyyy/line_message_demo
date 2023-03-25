<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\RawMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineApiService extends LINEBot
{
    protected string $channelSecret;
    protected HTTPClient $httpClient;

    public function __construct(HTTPClient $httpClient, array $args)
    {
        parent::__construct($httpClient, $args);
        $this->httpClient = $httpClient;
        $this->channelSecret = $args['channelSecret'];
    }

    public function sendTextMessage(string $userId, string $text)
    {
        $messageBuilder = new MultiMessageBuilder();
        $sendText = new TextMessageBuilder($text);
        $messageBuilder->add($sendText);
        $response = $this->pushMessage($userId, $messageBuilder);
        if ($response->isSucceeded()) {
            Log::info('sendTextMessage user id: '.$userId.':'.$response->getRawBody());

            return true;
        }

        Log::error('sendTextMessage failed user id: '.$userId.':'.$response->getJSONDecodedBody()['message']);

        return $response->getJSONDecodedBody()['message'];
    }

    public function sendFlexMessage(string $userId, array $array)
    {
        $response = $this->pushMessage($userId, new RawMessageBuilder($array));

        if ($response->isSucceeded()) {
            Log::info('sendFlexMessage user id: '.$userId.':'.$response->getRawBody());

            return true;
        }

        Log::error('sendFlexMessage failed user id: '.$userId.':'.$response->getJSONDecodedBody()['message']);

        return $response->getJSONDecodedBody()['message'];
    }

    public function sendImageMessage(string $userId, string $url, string $previewUrl = '')
    {
        if ($previewUrl === '') {
            $previewUrl = $url;
        }
        $messageBuilder = new ImageMessageBuilder($url, $previewUrl);

        $response = $this->pushMessage($userId, $messageBuilder);
        if ($response->isSucceeded()) {
            Log::info('sendImageMessage user id: '.$userId.':'.$response->getRawBody());

            return true;
        }

        Log::error('sendImageMessage failed user id: '.$userId.':'.$response->getJSONDecodedBody()['message']);

        return $response->getJSONDecodedBody()['message'];
    }
}
