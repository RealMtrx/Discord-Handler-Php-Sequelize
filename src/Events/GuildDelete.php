<?php

namespace DiscordHandler\Events;

use Discord\Discord;
use Discord\Parts\Guild\Guild;
use DiscordHandler\Handlers\Logger;
use DiscordHandler\Core\LeaveGuildWebhook;

class GuildDelete
{
    public function execute(Guild $guild, Discord $discord): void
    {
        Logger::info("Left guild: {$guild->name} ({$guild->id})");

        LeaveGuildWebhook::send($guild);
    }
}
