<?php

namespace App\Console\Commands;

use App\Enums\Vote;
use App\Models\Game;
use App\Models\TelegramUser;
use App\Services\GameService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Poll;

class SendGameCommand extends Command
{
    protected $signature = 'game:send';
    protected $description = 'Command description';

    public function handle(GameService $game): void
    {
        $lastGame = Game::orderBy('id', 'desc')->first();

        if ($lastGame)
            $game->import($lastGame->state);

        /** @var Poll|null $poll */
        $poll = null;

        try {
            $poll = Telegram::stopPoll([
                'chat_id' => Cache::get('last_poll_chat_id'),
                'message_id' => Cache::get('last_poll_message_id'),
            ]);
        } catch (Exception) {
        }

        $votes = collect([
            Vote::EMPTY->value => 0,
            Vote::UP->value => 0,
            Vote::DOWN->value => 0,
            Vote::LEFT->value => 0,
            Vote::RIGHT->value => 0
        ]);

        if ($poll) {
            foreach ($poll->options as $option) {
                $vote = Vote::from($option->text);
                $votes[$vote->value] = $option->voterCount;
            }
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

        $subscribedUsers = TelegramUser::where('is_subscribed', true)->get();

        /** @var Message $pollMessage */
        $pollMessage = null;

        foreach ($subscribedUsers as $user) {
            try {
                Telegram::sendMessage([
                    "chat_id" => $user->telegram_id,
                    "text" => $export,
                ]);

                if ($pollMessage) {
                    Telegram::forwardMessage([
                        'chat_id' => $user->telegram_id,
                        'from_chat_id' => $pollMessage->chat->id,
                        'message_id' => $pollMessage->messageId,
                    ]);
                } else {
                    $cases = collect(Vote::cases())
                        ->filter(fn(Vote $vote) => $vote != Vote::EMPTY)
                        ->values()
                        ->toArray();

                    $pollMessage = Telegram::sendPoll([
                        'chat_id' => $user->telegram_id,
                        'question' => 'Vote for next move',
                        'options' => $cases,
                        'is_anonymous' => false,
                    ]);
                }
            } catch (Exception) {
            }
        }

        Cache::put('last_poll_message_id', $pollMessage->messageId);
        Cache::put('last_poll_chat_id', $pollMessage->chat->id);
    }
}
