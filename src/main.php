<?php

require __DIR__ . '/../vendor/autoload.php';

use Discord\Discord;
use DiscordHandler\Config;
use DiscordHandler\Database\Eloquent;
use DiscordHandler\Handlers\AntiCrash;
use DiscordHandler\Handlers\Events;
use DiscordHandler\Handlers\Commands;
use DiscordHandler\Core\Webhooks;

Config::load(__DIR__ . '/..');

AntiCrash::register();

Eloquent::boot();

Events::register('READY', \DiscordHandler\Events\Ready::class);
Events::register('MESSAGE_CREATE', \DiscordHandler\Events\MessageCreate::class);
Events::register('INTERACTION_CREATE', \DiscordHandler\Events\InteractionCreate::class);
Events::register('GUILD_CREATE', \DiscordHandler\Events\GuildCreate::class);
Events::register('GUILD_DELETE', \DiscordHandler\Events\GuildDelete::class);

Commands::registerSlash('ping', \DiscordHandler\Commands\Slash\Public\PingCommand::class);
Commands::registerPrefix('ping', \DiscordHandler\Commands\Prefix\Public\PingCommand::class);

$discord = new Discord([
    'token' => Config::get('DISCORD_TOKEN'),
    'loadAllMembers' => true,
    'storeChannels' => true,
]);

Webhooks::setDiscord($discord);

Events::bind($discord);

$discord->run();
