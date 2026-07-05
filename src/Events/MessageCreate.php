<?php

namespace DiscordHandler\Events;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use DiscordHandler\Handlers\Prefix;
use DiscordHandler\Handlers\Commands;

class MessageCreate
{
    public function execute(Message $message, Discord $discord): void
    {
        if ($message->author->bot) {
            return;
        }

        $parsed = Prefix::parse($message->content);

        if ($parsed === null) {
            return;
        }

        Commands::handlePrefix($parsed['command'], $message, $discord);
    }
}
