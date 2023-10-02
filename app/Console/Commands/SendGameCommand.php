<?php

namespace App\Console\Commands;

use App\Enums\Vote;
use App\Models\TelegramUser;
use App\Services\GameService;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendGameCommand extends Command
{
    protected $signature = 'game:send';
    protected $description = 'Command description';

    public function handle(GameService $game): void
    {
        $allUsers = TelegramUser::all();

        $votes = collect(["up" => 0, "down" => 0, "left" => 0, "right" => 0]);

        foreach ($allUsers as $user) {
            // TODO: Fix This
            $vote = Vote::from($user->vote);

            if ($vote == Vote::EMPTY)
                continue;

            $votes[$vote->value]++;
        }

        $values = $votes->filter(fn ($vote) => $vote == $votes->max())
                        ->keys()
                        ->random();

        $subscribedUsers = TelegramUser::where('is_subscribed', true)->get();

        foreach ($subscribedUsers as $user) {
            Telegram::sendMessage([
                "chat_id" => $user->telegram_id,
                "text" => $game->export()
            ]);
        }
    }
}
