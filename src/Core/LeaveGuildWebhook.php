<?php

namespace DiscordHandler\Core;

use DiscordHandler\Config;

class LeaveGuildWebhook
{
    public static function send($guild): void
    {
        $url = Config::get('WEBHOOK_LEAVE_GUILD_URL');

        if (empty($url)) {
            return;
        }

        $embed = [
            'title' => 'Left Guild',
            'description' => "**{$guild->name}** ({$guild->id})",
            'color' => 0xFF0000,
            'thumbnail' => ['url' => $guild->icon ? "https://cdn.discordapp.com/icons/{$guild->id}/{$guild->icon}.png" : null],
            'fields' => [
                ['name' => 'Owner', 'value' => (string)$guild->owner_id, 'inline' => true],
                ['name' => 'Members', 'value' => (string)$guild->member_count, 'inline' => true],
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
