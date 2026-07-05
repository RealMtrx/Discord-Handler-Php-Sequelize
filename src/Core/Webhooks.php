<?php

namespace DiscordHandler\Core;

use Discord\Discord;
use Discord\Webhook;

abstract class Webhooks
{
    protected static ?Discord $discord = null;

    public static function setDiscord(Discord $discord): void
    {
        self::$discord = $discord;
    }

    protected static function sendMessage(string $url, string $content): void
    {
        if (empty($url)) {
            return;
        }

        $webhook = new Webhook(self::$discord, ['url' => $url]);
        $webhook->send($content);
    }

    protected static function sendEmbed(string $url, array $embed): void
    {
        if (empty($url)) {
            return;
        }

        $webhook = new Webhook(self::$discord, ['url' => $url]);
        $webhook->send('', false, [$embed]);
    }
}
