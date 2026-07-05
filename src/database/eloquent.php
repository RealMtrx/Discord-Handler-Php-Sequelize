<?php

namespace DiscordHandler\Database;

use DiscordHandler\Config;
use Illuminate\Database\Capsule\Manager as Capsule;

class Eloquent
{
    private static bool $booted = false;

    public static function boot(): void
    {
        if (self::$booted) {
            return;
        }

        $capsule = new Capsule;

        $dialect = Config::get('DB_DIALECT', 'sqlite');

        $config = match ($dialect) {
            'sqlite' => [
                'driver' => 'sqlite',
                'database' => Config::get('DB_STORAGE', __DIR__ . '/../../database.sqlite'),
                'prefix' => '',
            ],
            'mysql' => [
                'driver' => 'mysql',
                'host' => Config::get('DB_HOST', '127.0.0.1'),
                'port' => Config::get('DB_PORT', '3306'),
                'database' => Config::get('DB_DATABASE', 'discord'),
                'username' => Config::get('DB_USERNAME', 'root'),
                'password' => Config::get('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ],
            'pgsql' => [
                'driver' => 'pgsql',
                'host' => Config::get('DB_HOST', '127.0.0.1'),
                'port' => Config::get('DB_PORT', '5432'),
                'database' => Config::get('DB_DATABASE', 'discord'),
                'username' => Config::get('DB_USERNAME', 'root'),
                'password' => Config::get('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'prefix' => '',
            ],
            default => throw new \InvalidArgumentException("Unsupported DB_DIALECT: $dialect"),
        };

        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        self::$booted = true;
    }
}
