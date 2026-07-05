<?php

namespace DiscordHandler\Commands\Prefix\Public;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use DiscordHandler\Core\Emojis;

class PingCommand
{
    public function execute(Message $message, array $args, Discord $discord): void
    {
        $latency = $discord->ping;

        $message->channel->sendMessage(Emojis::PING . " Pong! Latency: {$latency}ms");
    }
}
