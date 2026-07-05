<?php

namespace DiscordHandler;

use Dotenv\Dotenv;

class Config
{
    private static array $cache = [];

    public static function load(string $dir): void
    {
        $dotenv = Dotenv::createImmutable($dir);
        $dotenv->safeLoad();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
