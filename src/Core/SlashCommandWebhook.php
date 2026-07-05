<?php

namespace DiscordHandler\Core;

use DiscordHandler\Config;

class SlashCommandWebhook
{
    public static function send($interaction, string $commandName): void
    {
        $url = Config::get('WEBHOOK_SLASH_COMMAND_URL');

        if (empty($url)) {
            return;
        }

        $embed = [
            'title' => 'Slash Command Used',
            'description' => "**/{$commandName}**",
            'color' => 0x3498DB,
            'fields' => [
                ['name' => 'User', 'value' => "{$interaction->member->user->username}#{$interaction->member->user->discriminator}", 'inline' => true],
                ['name' => 'User ID', 'value' => $interaction->member->user->id, 'inline' => true],
                ['name' => 'Guild', 'value' => $interaction->guild_id, 'inline' => true],
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
