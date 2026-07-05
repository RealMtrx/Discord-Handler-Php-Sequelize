<?php

namespace DiscordHandler\Handlers;

use Discord\Discord;
use Discord\Parts\Interactions\Command;
use Discord\Parts\Interactions\Interaction;
use DiscordHandler\Core\SlashCommandWebhook;
use DiscordHandler\Core\PrefixCommandWebhook;
use DiscordHandler\Core\CommandUtils;
use DiscordHandler\Core\Cooldown;

class Commands
{
    private static array $slashCommands = [];
    private static array $prefixCommands = [];

    public static function registerSlash(string $name, string $class): void
    {
        self::$slashCommands[$name] = $class;
    }

    public static function registerPrefix(string $name, string $class): void
    {
        self::$prefixCommands[$name] = $class;
    }

    public static function loadSlashCommands(Discord $discord): void
    {
        foreach (self::$slashCommands as $name => $class) {
            $instance = new $class;
            $command = new Command($discord, [
                'name' => $name,
                'description' => $instance->description ?? 'No description',
            ]);

            if (isset($instance->options)) {
                $command->options = $instance->options;
            }

            $discord->application->commands->save($command);
        }
    }

    public static function handleSlash(Interaction $interaction, Discord $discord): void
    {
        $name = $interaction->data->name;

        if (!isset(self::$slashCommands[$name])) {
            $interaction->respondWithMessage(\Discord\Builders\MessageBuilder::new()
                ->setContent('Command not found.'), true);
            return;
        }

        $userId = $interaction->member->user->id;

        if (Cooldown::isOnCooldown($userId, "slash_$name")) {
            $remaining = Cooldown::getRemaining($userId, "slash_$name");
            $interaction->respondWithMessage(\Discord\Builders\MessageBuilder::new()
                ->setContent("Please wait {$remaining}s before using this command again."), true);
            return;
        }

        Cooldown::setCooldown($userId, "slash_$name", 3);

        try {
            $instance = new self::$slashCommands[$name];
            $instance->execute($interaction, $discord);
            SlashCommandWebhook::send($interaction, $name);
        } catch (\Throwable $e) {
            $interaction->respondWithMessage(\Discord\Builders\MessageBuilder::new()
                ->setContent('An error occurred while executing the command.'), true);
            throw $e;
        }
    }

    public static function handlePrefix(string $commandName, $message, Discord $discord): void
    {
        $name = strtolower($commandName);

        if (!isset(self::$prefixCommands[$name])) {
            return;
        }

        $userId = $message->author->id;

        if (Cooldown::isOnCooldown($userId, "prefix_$name")) {
            $remaining = Cooldown::getRemaining($userId, "prefix_$name");
            $message->channel->sendMessage("Please wait {$remaining}s before using this command again.");
            return;
        }

        Cooldown::setCooldown($userId, "prefix_$name", 3);

        try {
            $args = CommandUtils::parseArgs($message->content);
            $instance = new self::$prefixCommands[$name];
            $instance->execute($message, $args, $discord);
            PrefixCommandWebhook::send($message, $name);
        } catch (\Throwable $e) {
            $message->channel->sendMessage('An error occurred while executing the command.');
            throw $e;
        }
    }
}
