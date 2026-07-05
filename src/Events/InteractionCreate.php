<?php

namespace DiscordHandler\Events;

use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use DiscordHandler\Handlers\Commands;

class InteractionCreate
{
    public function execute(Interaction $interaction, Discord $discord): void
    {
        if ($interaction->type !== \Discord\InteractionType::ApplicationCommand) {
            return;
        }

        Commands::handleSlash($interaction, $discord);
    }
}
