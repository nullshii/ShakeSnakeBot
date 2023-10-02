<?php

namespace App\Console\Commands;

use App\Models\TelegramUser;
use App\Services\GameService;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TestGameCommand extends Command
{
    protected $signature = "game:test";
    protected $description = "Test game to log file.";

    public function handle(GameService $game): void
    {
        $users = TelegramUser::where('is_subscribed', true)->get();

        foreach ($users as $user) {
            Telegram::sendMessage([
                "chat_id" => $user->telegram_id,
                "text" => $game->export()
            ]);
        }
    }
}
