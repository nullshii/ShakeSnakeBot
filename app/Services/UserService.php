<?php

namespace App\Services;

use App\Models\TelegramUser;

class UserService
{
    public function FindOrCreateUser(string $userId, string $username = ""): ?TelegramUser {
        return TelegramUser::firstOrCreate(
            ['telegram_id' => $userId],
            ['telegram_id' => $userId, 'name' => $username]
        );
    }
}
