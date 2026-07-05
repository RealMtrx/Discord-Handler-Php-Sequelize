<?php

namespace DiscordHandler\Events;

use Discord\Discord;
use Discord\Parts\Guild\Guild;
use DiscordHandler\Handlers\Logger;
use DiscordHandler\Core\JoinGuildWebhook;

class GuildCreate
{
    public function execute(Guild $guild, Discord $discord): void
    {
        Logger::info("Joined guild: {$guild->name} ({$guild->id})");

        JoinGuildWebhook::send($guild);
    }
}
