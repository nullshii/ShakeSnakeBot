<?php

namespace App\Console\Commands;

use App\Enums\CellType;
use App\Enums\Vote;
use App\Game\Renderer;
use App\Game\Vector2;
use App\Models\Game;
use App\Services\GameService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Imagick;
use ImagickException;
use ImagickPixel;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Poll;
use function Termwind\render;

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

        $nextGame = new Game();
        $nextGame->state = $game->export();
        $nextGame->save();

        $channelId = config('telegram.channelId');

        try {
            $renderer = new Renderer($game);
            $image = $renderer->render();

            Telegram::sendPhoto([
                'chat_id' => $channelId,
                'photo' => InputFile::createFromContents($image->getImageBlob(), 'game.png'),
            ]);
        } catch (Exception) {
        }

        $cases = collect(Vote::cases())
            ->filter(fn(Vote $vote) => $vote != Vote::EMPTY)
            ->values()
            ->toArray();

        try {
            $pollMessage = Telegram::sendPoll([
                'chat_id' => $channelId,
                'question' => 'Vote for next move',
                'options' => $cases,
            ]);

            Cache::put('last_poll_message_id', $pollMessage->messageId);
            Cache::put('last_poll_chat_id', $pollMessage->chat->id);
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
