<?php

namespace App\Bot\Commands;

use App\Services\UserService;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class Subscribe extends Command
{
    protected string $name = 'subscribe';
    protected string $description = 'Subscribe to game updates';

    public function __construct(protected UserService $userService)
    {
    }

    public function handle(): void
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $userId = $this->update->message->from->id;

        $user = $this->userService->FindOrCreateUser($userId);
        if (!$user) {
            $this->replyWithMessage([
                'text' => 'Sorry. Something went wrong. Please contact with admin: @nullshii'
            ]);
            return;
        }

        if ($user->is_subscribed) {
            $this->replyWithMessage([
                'text' => 'You already subscribed to updates.'
            ]);
            return;
        }

        $user->update(['is_subscribed' => true]);

        $this->replyWithMessage([
            'text' => 'Successfully subscribed to updates.'
        ]);
    }
}
