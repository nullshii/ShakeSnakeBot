<?php

namespace App\Bot\Commands\Vote;

use App\Enums\Vote;
use App\Services\UserService;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class UnVote extends Command
{
    protected string $name = "unvote";
    protected string $description = "Remove current vote";

    public function __construct(private readonly UserService $userService)
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

        if (!$user->vote) {
            $this->replyWithMessage([
                'text' => "$name didn't even voted.",
                'reply_markup' => Keyboard::remove(['selective' => true]),
            ]);
            return;
        }

        $this->replyWithMessage([
            'text' => "$name removed vote",
            'reply_markup' => Keyboard::remove(['selective' => true])
        ]);

        $user->update(['vote' => null]);
    }
}
