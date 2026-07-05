<?php

namespace DiscordHandler\Core;

class Cooldown
{
    private static array $cooldowns = [];

    public static function setCooldown(string $userId, string $key, int $seconds): void
    {
        self::$cooldowns[$userId][$key] = time() + $seconds;
    }

    public static function isOnCooldown(string $userId, string $key): bool
    {
        return isset(self::$cooldowns[$userId][$key]) && self::$cooldowns[$userId][$key] > time();
    }

    public static function getRemaining(string $userId, string $key): int
    {
        if (!isset(self::$cooldowns[$userId][$key])) {
            return 0;
        }

        $remaining = self::$cooldowns[$userId][$key] - time();
        return max(0, $remaining);
    }

    public static function clearCooldown(string $userId, string $key): void
    {
        unset(self::$cooldowns[$userId][$key]);
    }

    public static function clearAll(string $userId): void
    {
        unset(self::$cooldowns[$userId]);
    }
}
