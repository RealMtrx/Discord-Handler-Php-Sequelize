<?php

namespace DiscordHandler\Core;

class CommandUtils
{
    public static function parseArgs(string $content): array
    {
        $parts = preg_split('/\s+/', trim($content));
        array_shift($parts);
        return $parts;
    }

    public static function getArg(array $args, int $index, mixed $default = null): mixed
    {
        return $args[$index] ?? $default;
    }

    public static function hasFlag(array $args, string $flag): bool
    {
        return in_array("--$flag", $args) || in_array("-$flag", $args);
    }
}
