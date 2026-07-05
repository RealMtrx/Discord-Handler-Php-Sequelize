<?php

namespace DiscordHandler\Handlers;

use Discord\Discord;

class Logger
{
    private static bool $enabled = true;

    public static function disable(): void
    {
        self::$enabled = false;
    }

    public static function enable(): void
    {
        self::$enabled = true;
    }

    public static function info(string $message): void
    {
        if (!self::$enabled) return;
        echo "[INFO] " . date('Y-m-d H:i:s') . " - $message" . PHP_EOL;
    }

    public static function warn(string $message): void
    {
        if (!self::$enabled) return;
        echo "[WARN] " . date('Y-m-d H:i:s') . " - $message" . PHP_EOL;
    }

    public static function error(string $message): void
    {
        if (!self::$enabled) return;
        echo "[ERROR] " . date('Y-m-d H:i:s') . " - $message" . PHP_EOL;
    }

    public static function debug(string $message): void
    {
        if (!self::$enabled) return;
        echo "[DEBUG] " . date('Y-m-d H:i:s') . " - $message" . PHP_EOL;
    }
}
