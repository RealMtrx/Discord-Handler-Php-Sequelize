# Discord Handler PHP Sequelize

A Discord bot framework using DiscordPHP v7 and Eloquent ORM (Capsule).

## Installation

```bash
composer install
cp .env.example .env
```

Edit `.env` with your bot token and database settings.

## Usage

```bash
php src/main.php
```

## Features

- Slash commands and prefix commands
- Event handling system
- Eloquent ORM integration
- Webhook logging (errors, guild joins/leaves, ready, commands)
- Cooldown system
- Anti-crash handler
