<div align="center">
  <h1>Discord Handler — PHP (SQL Edition)</h1>
  <p><strong>A production-ready Discord bot framework built with DiscordPHP and Eloquent — supports SQLite, PostgreSQL, and MySQL with a modular src/ architecture.</strong></p>

  <p>
    <a href="https://github.com/RealMtrx/Discord-Handler-Php-Sequelize/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
    <a href="https://github.com/RealMtrx/Discord-Handler-Php-Sequelize/releases"><img src="https://img.shields.io/badge/version-0.9.0--beta-yellow" alt="Version 0.9.0 Beta"></a>
    <a href="https://github.com/RealMtrx/Discord-Handler-Php-Sequelize/stargazers"><img src="https://img.shields.io/github/stars/RealMtrx/Discord-Handler-Php-Sequelize" alt="Stars"></a>
    <a href="https://github.com/RealMtrx/Discord-Handler-Php-Sequelize/issues"><img src="https://img.shields.io/github/issues/RealMtrx/Discord-Handler-Php-Sequelize" alt="Issues"></a>
    <a href="https://github.com/RealMtrx/Discord-Handler-Php-Sequelize/network"><img src="https://img.shields.io/github/forks/RealMtrx/Discord-Handler-Php-Sequelize" alt="Forks"></a>
    <a href="https://github.com/RealMtrx/Discord-Handler/graphs/contributors"><img src="https://img.shields.io/badge/ecosystem-26%20repos-brightgreen" alt="26 Repos"></a>
    <a href="https://discord.gg/0hu2"><img src="https://img.shields.io/badge/discord-0hu2-5865F2" alt="Discord"></a>
  </p>

  <br>

  <p>
    <a href="#-features">Features</a> •
    <a href="#-quick-start">Quick Start</a> •
    <a href="#-project-structure">Structure</a> •
    <a href="#-database-configuration">Database Config</a> •
    <a href="#-api-reference">API</a> •
    <a href="#-mongodb-edition">MongoDB Edition</a> •
    <a href="#-related-repositories">Ecosystem</a>
  </p>
</div>

---

## Overview

An SQL edition of the Discord Handler framework built with **PHP**, **DiscordPHP v7** (Discord library), and **Eloquent ORM** via Laravel's Capsule (SQLite / PostgreSQL / MySQL). It mirrors the architecture of the MongoDB edition, replacing document storage with relational database support while keeping the same modular structure, anti-crash protection, webhook logging, and dual command system.

## Features

- **Dual Command System** — Slash commands and prefix commands
- **Event-Driven Architecture** — Fully event-driven async architecture with ReactPHP
- **Eloquent ORM (Capsule)** — Laravel's beautiful ActiveRecord implementation
- **Anti-Crash Handler** — Global error catching that keeps your bot online
- **Webhook Error Logging** — Real-time reporting for errors, guild joins/leaves, ready, and commands
- **Cooldown System** — Per-command rate limiting
- **Emoji Constants** — Centralized unicode emoji definitions
- **Environment Configuration** — Secure token management via phpdotenv

## Quick Start

```bash
# Clone
git clone https://github.com/RealMtrx/Discord-Handler-Php-Sequelize.git
cd Discord-Handler-Php-Sequelize

# Install dependencies
composer install

# Configure
cp .env.example .env
# Edit .env with your bot token and database settings

# Run the bot
php src/main.php
```

## Project Structure

```
src/
├── main.php                       # Entry point
├── config.php                     # .env configuration loader
├── database/
│   └── eloquent.php               # Eloquent Capsule connection setup
├── Models/
│   └── User.php                   # Eloquent User model
├── Handlers/
│   ├── AntiCrash.php              # Global error handler
│   ├── Commands.php               # Slash command loader
│   ├── Events.php                 # Event loader
│   ├── Logger.php                 # Logging utility
│   └── Prefix.php                 # Prefix command handler
├── Events/
│   ├── Ready.php                  # Bot ready event
│   ├── MessageCreate.php          # Message create listener
│   ├── InteractionCreate.php      # Interaction create listener
│   ├── GuildCreate.php            # Guild create listener
│   └── GuildDelete.php            # Guild delete listener
├── Core/
│   ├── Webhooks.php               # Base webhook utility
│   ├── ErrorWebhook.php           # Error webhook reporter
│   ├── JoinGuildWebhook.php       # Guild join webhook
│   ├── LeaveGuildWebhook.php      # Guild leave webhook
│   ├── ReadyWebhook.php           # Ready event webhook
│   ├── SlashCommandWebhook.php    # Slash command webhook
│   ├── PrefixCommandWebhook.php   # Prefix command webhook
│   ├── Emojis.php                 # Emoji constants
│   ├── CommandUtils.php           # Command helpers
│   └── Cooldown.php               # Cooldown manager
└── Commands/
    ├── Slash/Public/PingCommand.php   # Example slash command
    └── Prefix/Public/PingCommand.php  # Example prefix command
```

## Database Configuration

Configure your database in `.env`:

```env
DISCORD_TOKEN=your_bot_token_here
DISCORD_PREFIX=!

DB_DIALECT=sqlite        # sqlite | mysql | pgsql
DB_STORAGE=database.sqlite  # SQLite file path (sqlite only)
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=discord
DB_USERNAME=root
DB_PASSWORD=

WEBHOOK_ERROR_URL=
WEBHOOK_JOIN_GUILD_URL=
WEBHOOK_LEAVE_GUILD_URL=
WEBHOOK_READY_URL=
WEBHOOK_SLASH_COMMAND_URL=
WEBHOOK_PREFIX_COMMAND_URL=
```

### Dialect examples

**SQLite (default)** — zero configuration, file-based:
```env
DB_DIALECT=sqlite
DB_STORAGE=database.sqlite
```

**MySQL**:
```env
DB_DIALECT=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=discord
DB_USERNAME=root
DB_PASSWORD=your_password
```

**PostgreSQL**:
```env
DB_DIALECT=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=discord
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

## API Reference

### Eloquent Capsule Setup (`src/database/eloquent.php`)

```php
<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => env('DB_DIALECT', 'sqlite'),
    'database' => env('DB_DIALECT') === 'sqlite'
        ? __DIR__ . '/../../' . env('DB_STORAGE', 'database.sqlite')
        : env('DB_DATABASE', 'discord'),
    'host'     => env('DB_HOST', '127.0.0.1'),
    'port'     => env('DB_PORT', 3306),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset'  => 'utf8mb4',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
```

### Eloquent Model Example (`src/Models/User.php`)

```php
<?php

namespace DiscordHandler\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['id', 'username', 'discriminator'];
    public $incrementing = false;
    protected $keyType = 'string';
}
```

### Slash Command Example (`src/Commands/Slash/Public/PingCommand.php`)

```php
<?php

namespace DiscordHandler\Commands\Slash\Public;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

class PingCommand
{
    const NAME = 'ping';
    const DESCRIPTION = 'Replies with Pong!';

    public static function execute(Interaction $interaction): void
    {
        $interaction->respond(
            MessageBuilder::new()->setContent('Pong!')
        );
    }
}
```

### Prefix Command Example (`src/Commands/Prefix/Public/PingCommand.php`)

```php
<?php

namespace DiscordHandler\Commands\Prefix\Public;

use Discord\Parts\Channel\Message;

class PingCommand
{
    const NAME = 'ping';

    public static function execute(Message $message): void
    {
        $message->reply('Pong!');
    }
}
```

## Adding Commands

1. Create a file in `src/Commands/Slash/Public/<name>Command.php` or `src/Commands/Prefix/Public/<name>Command.php`
2. Define a class with `NAME`, `DESCRIPTION` (slash only), and a static `execute` method
3. The command loader automatically picks up new files

## MongoDB Edition

Prefer a document database? Use the **MongoDB edition** of this handler:

<div align="center">
  <a href="https://github.com/RealMtrx/Discord-Handler-Php"><img src="https://img.shields.io/badge/Discord--Handler--Php-MongoDB%20Edition-blue?style=for-the-badge" alt="MongoDB Edition"></a>
</div>

The MongoDB edition uses `mongodb/mongodb` instead of Eloquent, but shares the same command, event, and handler structure. You can switch between editions without relearning the architecture.

## Related Repositories

The Discord Handler ecosystem includes **26 repositories** — 13 Core Framework (MongoDB) editions and 13 Database (SQL) editions, covering 13 programming languages.

### Core Framework (MongoDB) Editions

| # | Language | Repository |
|---|----------|------------|
| 1 | JavaScript | [Discord-Handler-Js](https://github.com/RealMtrx/Discord-Handler-Js) |
| 2 | TypeScript | [Discord-Handler-Ts](https://github.com/RealMtrx/Discord-Handler-Ts) |
| 3 | Go | [Discord-Handler-Go](https://github.com/RealMtrx/Discord-Handler-Go) |
| 4 | Rust | [Discord-Handler-Rs](https://github.com/RealMtrx/Discord-Handler-Rs) |
| 5 | Python | [Discord-Handler-Py](https://github.com/RealMtrx/Discord-Handler-Py) |
| 6 | C# | [Discord-Handler-Cs](https://github.com/RealMtrx/Discord-Handler-Cs) |
| 7 | Java | [Discord-Handler-Java](https://github.com/RealMtrx/Discord-Handler-Java) |
| 8 | Kotlin | [Discord-Handler-Kt](https://github.com/RealMtrx/Discord-Handler-Kt) |
| 9 | C++ | [Discord-Handler-Cpp](https://github.com/RealMtrx/Discord-Handler-Cpp) |
| 10 | Dart | [Discord-Handler-Dart](https://github.com/RealMtrx/Discord-Handler-Dart) |
| 11 | Ruby | [Discord-Handler-Rb](https://github.com/RealMtrx/Discord-Handler-Rb) |
| 12 | Lua | [Discord-Handler-Lua](https://github.com/RealMtrx/Discord-Handler-Lua) |
| 13 | **PHP** | [Discord-Handler-Php](https://github.com/RealMtrx/Discord-Handler-Php) |

### Database (SQL) Editions

| # | Language | Repository | ORM |
|---|----------|------------|-----|
| 1 | JavaScript | [Discord-Handler-Js-Sequelize](https://github.com/RealMtrx/Discord-Handler-Js-Sequelize) | Sequelize |
| 2 | TypeScript | [Discord-Handler-Ts-Sequelize](https://github.com/RealMtrx/Discord-Handler-Ts-Sequelize) | Sequelize |
| 3 | Go | [Discord-Handler-Go-Sequelize](https://github.com/RealMtrx/Discord-Handler-Go-Sequelize) | GORM |
| 4 | Rust | [Discord-Handler-Rs-Sequelize](https://github.com/RealMtrx/Discord-Handler-Rs-Sequelize) | Diesel |
| 5 | Python | [Discord-Handler-Py-Sequelize](https://github.com/RealMtrx/Discord-Handler-Py-Sequelize) | SQLAlchemy |
| 6 | C# | [Discord-Handler-Cs-Sequelize](https://github.com/RealMtrx/Discord-Handler-Cs-Sequelize) | EF Core |
| 7 | Java | [Discord-Handler-Java-Sequelize](https://github.com/RealMtrx/Discord-Handler-Java-Sequelize) | Hibernate |
| 8 | Kotlin | [Discord-Handler-Kt-Sequelize](https://github.com/RealMtrx/Discord-Handler-Kt-Sequelize) | Exposed |
| 9 | C++ | [Discord-Handler-Cpp-Sequelize](https://github.com/RealMtrx/Discord-Handler-Cpp-Sequelize) | sqlpp11 |
| 10 | Dart | [Discord-Handler-Dart-Sequelize](https://github.com/RealMtrx/Discord-Handler-Dart-Sequelize) | drift |
| 11 | Ruby | [Discord-Handler-Rb-Sequelize](https://github.com/RealMtrx/Discord-Handler-Rb-Sequelize) | Sequel |
| 12 | Lua | [Discord-Handler-Lua-Sequelize](https://github.com/RealMtrx/Discord-Handler-Lua-Sequelize) | LuaSQL |
| 13 | **PHP** | **Discord-Handler-Php-Sequelize** | **Eloquent** |

### Hub Repository

<div align="center">
  <a href="https://github.com/RealMtrx/Discord-Handler"><img src="https://img.shields.io/badge/Hub-Discord--Handler-181717?style=for-the-badge&logo=github" alt="Hub Repository"></a>
</div>

The hub repo contains documentation, examples in every language, changelog, roadmap, and contribution guidelines.

## License

Distributed under the MIT License. See `LICENSE` for more information.

---

Built by **Mtrx** — Discord: **0hu2**
