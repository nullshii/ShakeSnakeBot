<?php

namespace App\Bot\Commands\Vote;

use App\Enums\Vote;
use App\Services\UserService;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class VoteBase extends Command
{
    protected Vote $vote = Vote::EMPTY;

    public function __construct(protected UserService $userService)
    {
    }

    public function handle(): void
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $user = $this->userService->FindOrCreateUser($this->update->message->from->id);
        if (!$user) {
            $this->replyWithMessage([
                'text' => 'Sorry. Something went wrong. Please contact with admin: @nullshii'
            ]);
            return;
        }

        $name = ($username = $this->update->message->from->username)
            ? ('@' . $username)
            : $this->update->message->from->firstName;

        if ($user->vote != 0) {
            $voteEmoji = Vote::from($user->vote)->emoji();

            $this->replyWithMessage([
                'text' => "$name already voted $voteEmoji",
                'reply_markup' => Keyboard::remove(['selective' => true]),
            ]);
            return;
        }

        $this->replyWithMessage([
            'text' => "$name voted for {$this->vote->emoji()}",
            'reply_markup' => Keyboard::remove(['selective' => true])
        ]);

        $user->update(['vote' => $this->vote]);
    }
}
