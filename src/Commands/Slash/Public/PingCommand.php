<?php

namespace DiscordHandler\Commands\Slash\Public;

use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use DiscordHandler\Core\Emojis;

class PingCommand
{
    public string $description = 'Check the bot\'s latency';
    public array $options = [];

    public function execute(Interaction $interaction, Discord $discord): void
    {
        $latency = $discord->ping;

        $interaction->respondWithMessage(
            \Discord\Builders\MessageBuilder::new()
                ->setContent(Emojis::PING . " Pong! Latency: {$latency}ms")
        );
    }
}
