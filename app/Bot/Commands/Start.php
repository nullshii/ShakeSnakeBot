<?php

namespace App\Bot\Commands;

use App\Services\UserService;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class Start extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start bot';

    public function __construct(private readonly UserService $userService)
    {
    }

    public function handle(): void
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $userId = $this->update->message->from->id;
        $username = $this->update->message->from->username;

        $user = $this->userService->FindOrCreateUser($userId, $username);

        if (!$user) {
            $this->replyWithMessage([
                'text' => 'Sorry. Something went wrong. Please contact with admin: @nullshii'
            ]);
            return;
        }

        $this->replyWithMessage([
            'text' => "Welcome to the Shake Snake bot. Here, you can play snake with other players. To play this game, you need to vote for the next move, which happens every 5 minutes. To subscribe to game updates, use the /subscribe command. Or add this bot to the group chat to vote together. For additional information about commands, use the /help command.",
        ]);
    }
}
