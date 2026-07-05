<?php

namespace DiscordHandler\Core;

use DiscordHandler\Config;

class PrefixCommandWebhook
{
    public static function send($message, string $commandName): void
    {
        $url = Config::get('WEBHOOK_PREFIX_COMMAND_URL');

        if (empty($url)) {
            return;
        }

        $embed = [
            'title' => 'Prefix Command Used',
            'description' => "**{$commandName}**",
            'color' => 0x9B59B6,
            'fields' => [
                ['name' => 'User', 'value' => "{$message->author->username}#{$message->author->discriminator}", 'inline' => true],
                ['name' => 'User ID', 'value' => $message->author->id, 'inline' => true],
                ['name' => 'Channel', 'value' => $message->channel_id, 'inline' => true],
                ['name' => 'Content', 'value' => $message->content, 'inline' => false],
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
