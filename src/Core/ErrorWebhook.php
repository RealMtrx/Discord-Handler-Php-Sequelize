<?php

namespace DiscordHandler\Core;

use DiscordHandler\Config;

class ErrorWebhook
{
    public static function send(string $error): void
    {
        $url = Config::get('WEBHOOK_ERROR_URL');

        if (empty($url)) {
            return;
        }

        $embed = [
            'title' => 'Error',
            'description' => "```\n$error\n```",
            'color' => 0xFF0000,
            'timestamp' => date('c'),
        ];

        $payload = json_encode(['embeds' => [$embed]]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
