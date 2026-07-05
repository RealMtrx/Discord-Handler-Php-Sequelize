<?php

namespace DiscordHandler\Core;

use DiscordHandler\Config;

class ReadyWebhook
{
    public static function send($discord): void
    {
        $url = Config::get('WEBHOOK_READY_URL');

        if (empty($url)) {
            return;
        }

        $embed = [
            'title' => 'Bot Ready',
            'description' => "Logged in as **{$discord->user->displayname}**",
            'color' => 0x00FF00,
            'fields' => [
                ['name' => 'User ID', 'value' => $discord->user->id, 'inline' => true],
                ['name' => 'Guilds', 'value' => (string)$discord->guilds->count(), 'inline' => true],
            ],
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
