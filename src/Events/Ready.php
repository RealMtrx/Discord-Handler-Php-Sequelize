<?php

namespace DiscordHandler\Events;

use Discord\Discord;
use DiscordHandler\Handlers\Logger;
use DiscordHandler\Handlers\Commands;
use DiscordHandler\Core\ReadyWebhook;

class Ready
{
    public function execute(Discord $discord): void
    {
        Logger::info("Bot is ready! Logged in as {$discord->user->displayname}");

        Commands::loadSlashCommands($discord);

        ReadyWebhook::send($discord);
    }
}
