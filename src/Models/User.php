<?php

namespace DiscordHandler\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'user_id',
        'username',
        'discriminator',
        'avatar',
        'guild_id',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];
}
