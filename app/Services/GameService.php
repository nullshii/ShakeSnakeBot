<?php

namespace App\Services;

use App\Enums\CellType;
use App\Enums\Direction;
use App\Game\Map;
use App\Game\Snake;
use App\Models\TelegramUser;
use Exception;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class GameService
{
    private Map $map;
    private Snake $snake;

    public function __construct()
    {
        $this->map = new Map(10);
        $this->snake = new Snake($this->map);
    }

    public function win(): void
    {
        $subscribedUsers = TelegramUser::where('is_subscribed', true)->get();

        try {
            foreach ($subscribedUsers as $user) {
                Telegram::sendMessage([
                    "chat_id" => $user->telegram_id,
                    "text" => "You win",
                    'reply_markup' => Keyboard::remove(),
                ]);
            }
        } catch (Exception) {
        }

        $this->gameOver();
    }


    public function lose(): void
    {
        $subscribedUsers = TelegramUser::where('is_subscribed', true)->get();

        try {
            foreach ($subscribedUsers as $user) {
                Telegram::sendMessage([
                    "chat_id" => $user->telegram_id,
                    "text" => "You lose",
                    'reply_markup' => Keyboard::remove(),
                ]);
            }
        } catch (Exception) {
        }

        $this->gameOver();
    }

    public function onEat(): void
    {
        $this->map->createApple();
    }

    public function move(Direction $direction): void
    {
        $this->snake->move($direction);
    }

    public function getCurrentDirection(): Direction
    {
        $headCell = $this->map->getCellAtPosition($this->snake->getHeadPosition());

        return match ($headCell->getCellType())
        {
            default => Direction::UP,
            CellType::SNAKE_HEAD_DOWN => Direction::DOWN,
            CellType::SNAKE_HEAD_LEFT => Direction::LEFT,
            CellType::SNAKE_HEAD_RIGHT => Direction::RIGHT,
        };
    }

    public function export(): string
    {
        return $this->map->toString();
    }

    public function import(string $str): void
    {
        $this->map = Map::fromString($str);
        $this->snake = new Snake($this->map);
    }

    private function gameOver(): void
    {
        $this->map = new Map(10);
        $this->snake = new Snake($this->map);
    }
}
