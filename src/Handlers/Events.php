<?php

namespace DiscordHandler\Handlers;

use Discord\Discord;

class Events
{
    private static array $listeners = [];

    public static function register(string $event, string $class): void
    {
        self::$listeners[$event] = $class;
    }

    public static function bind(Discord $discord): void
    {
        foreach (self::$listeners as $event => $class) {
            $discord->on($event, function (...$args) use ($class, $discord) {
                try {
                    $instance = new $class;
                    $instance->execute(...$args, $discord);
                } catch (\Throwable $e) {
                    ErrorWebhook::send("[Event:$event] {$e->getMessage()}");
                }
            });
        }
    }
}
