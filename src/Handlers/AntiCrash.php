<?php

namespace DiscordHandler\Handlers;

use DiscordHandler\Core\ErrorWebhook;

class AntiCrash
{
    public static function register(): void
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        $error = "[Error] $message in $file on line $line";
        ErrorWebhook::send($error);
        return false;
    }

    public static function handleException(\Throwable $e): void
    {
        $error = "[Exception] {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}";
        ErrorWebhook::send($error);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $msg = "[Fatal] {$error['message']} in {$error['file']} on line {$error['line']}";
            ErrorWebhook::send($msg);
        }
    }
}
