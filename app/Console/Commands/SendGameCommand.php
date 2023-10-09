<?php

namespace App\Console\Commands;

use App\Enums\Vote;
use App\Models\Game;
use App\Models\TelegramUser;
use App\Services\GameService;
use Exception;
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

        if ($lastGame)
            $game->import($lastGame->state);

        $votes = collect([
            Vote::EMPTY->value => 0,
            Vote::UP->value => 0,
            Vote::DOWN->value => 0,
            Vote::LEFT->value => 0,
            Vote::RIGHT->value => 0
        ]);

        foreach (TelegramUser::all() as $user) {
            $vote = Vote::from($user->vote ?? 'empty');
            $votes[$vote->value] += 1;
            $user->vote = null;
            $user->save();
        }

        $filteredVotes = $votes->filter(fn($vote, $type) => $type != Vote::EMPTY->value);

        if ($filteredVotes->sum() > 0) {
            $valuableVote = Vote::from(
                $filteredVotes
                    ->filter(fn($vote) => $vote == $filteredVotes->max())
                    ->keys()
                    ->random()
            );
        } else {
            $valuableVote = Vote::EMPTY;
        }

        if ($lastGame) {
            $lastGame->vote = $valuableVote->value;
            $lastGame->save();
        }

        $game->move($valuableVote == Vote::EMPTY
            ? $game->getCurrentDirection()
            : $valuableVote->asDirection()
        );

        $export = $game->export();

        $nextGame = new Game();
        $nextGame->state = $export;
        $nextGame->save();

        $keyboard = new Keyboard([
            'keyboard' => [
                ['/help', '/vote_up', '/unvote'],
                ['/vote_left', '/vote_down', '/vote_right']
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => true,
        ]);

        $subscribedUsers = TelegramUser::where('is_subscribed', true)->get();

        foreach ($subscribedUsers as $user) {
            try {
                Telegram::sendMessage([
                    "chat_id" => $user->telegram_id,
                    "text" => $export,
                    'reply_markup' => $keyboard,
                ]);
            } catch (Exception) {
            }
        }
    }
}
