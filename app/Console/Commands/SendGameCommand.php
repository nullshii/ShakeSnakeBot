<?php

namespace App\Console\Commands;

use App\Enums\Vote;
use App\Models\Game;
use App\Models\TelegramUser;
use App\Services\GameService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendGameCommand extends Command
{
    protected $signature = 'game:send';
    protected $description = 'Command description';

    public function handle(GameService $game): void
    {
        $lastGame = Game::orderBy('id', 'desc')->first();

        if ($lastGame && !$lastGame->is_over) {
            $game->import($lastGame->state);
        } else {
            $game->initEmpty();
        }

        $votes = collect([
            Vote::UP->value => 0,
            Vote::DOWN->value => 0,
            Vote::LEFT->value => 0,
            Vote::RIGHT->value => 0
        ]);

        foreach (TelegramUser::all() as $user) {
            $vote = Vote::from($user->vote ?? '');

            if ($vote != Vote::EMPTY)
                $votes[$vote->value] += 1;

            $user->update(['vote' => null]);
        }

        $valuableVote = Vote::from(
            $votes->filter(fn($vote) => $vote == $votes->max())
                ->keys()
                ->random()
        );

        $lastGame?->update(['vote' => $valuableVote->value]);
        $subscribedUsers = TelegramUser::where('is_subscribed', true)->get();

        $nextGame = new Game();


        if ($game->nextVote($valuableVote)){
            foreach ($subscribedUsers as $user) {
                Telegram::sendMessage([
                    "chat_id" => $user->telegram_id,
                    "text" => "Game over",
                    'reply_markup' => Keyboard::remove(),
                ]);
            }

            $nextGame->state = $game->export();
            $nextGame->is_over = true;
            $nextGame->save();

            return;
        }

        $export = $game->export();
        $nextGame->state = $export;
        $nextGame->save();

        $keyboard = new Keyboard([
            'keyboard' => [
                [' ', '/vote_up', ' '],
                ['/vote_left', '/vote_down', '/vote_right']
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => true,
        ]);

        foreach ($subscribedUsers as $user) {
            Telegram::sendMessage([
                "chat_id" => $user->telegram_id,
                "text" => $export,
                'reply_markup' => $keyboard,
            ]);
        }
    }
}
