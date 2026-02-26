<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;

final class TurboSms
{
    public const API_URL = 'https://api.turbosms.ua/message/send.json';
    public const API_TOKEN = '250ccc400c0733d211f2cc83822efaef0f9e0ee5';

    public static function sendSms(array $recipients, int $text): void
    {
        $postInput = [
            'token' => self::API_TOKEN,
            'recipients' => $recipients,
            'sms' => [
                'sender' => env('APP_NAME'),
                'text' => $text
            ]
        ];

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Type: application/json'
        ];

        Http::withHeaders($headers)->post(self::API_URL, $postInput);
    }
}
