<?php

namespace App\Bot\Commands;

use App\Services\UserService;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class Unsubscribe extends Command
{
    protected string $name = 'unsubscribe';
    protected string $description = 'Unsubscribe from game updates';

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

        if (!$user->is_subscribed) {
            $this->replyWithMessage([
                'text' => 'You already unsubscribed from updates.'
            ]);
            return;
        }

        $user->update(['is_subscribed' => false]);

        $this->replyWithMessage([
            'text' => 'Successfully unsubscribed from updates.'
        ]);
    }
}
