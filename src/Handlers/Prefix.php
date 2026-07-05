<?php

namespace DiscordHandler\Handlers;

use Discord\Discord;
use DiscordHandler\Config;

class Prefix
{
    private static ?string $prefix = null;

    public static function get(): string
    {
        if (self::$prefix === null) {
            self::$prefix = Config::get('DISCORD_PREFIX', '!');
        }
        return self::$prefix;
    }

    public static function set(string $prefix): void
    {
        self::$prefix = $prefix;
    }

    public static function parse(string $content): ?array
    {
        $prefix = self::get();
        $len = strlen($prefix);

        if (substr($content, 0, $len) !== $prefix) {
            return null;
        }

        $parts = preg_split('/\s+/', substr($content, $len));
        $command = strtolower(array_shift($parts));

        return ['command' => $command, 'args' => $parts];
    }
}
