<?php

namespace App\Game;

use App\Enums\CellType;
use App\Services\GameService;
use Imagick;
use ImagickException;
use ImagickPixel;

class Renderer
{
    private string $headPath;
    private string $bodyPath;
    private string $tailPath;
    private string $wallPath;
    private string $applePath;

    public function __construct(private readonly GameService $gameService)
    {
        $this->headPath = resource_path("images/snake/Head.png");
        $this->bodyPath = resource_path("images/snake/Body.png");
        $this->tailPath = resource_path("images/snake/Tail.png");
        $this->wallPath = resource_path("images/snake/Wall.png");
        $this->applePath = resource_path("images/snake/Apple.png");
    }

    /**
     * @throws ImagickException
     */
    public function render(): Imagick
    {
        $img = new Imagick();
        $imageSize = 64 * $this->gameService->map->size;
        $img->newImage($imageSize, $imageSize, new ImagickPixel("white"), "png");

        $tailPosition = $this->gameService->snake->getTailPosition();

        for ($y = 0; $y < $this->gameService->map->size; $y++) {
            for ($x = 0; $x < $this->gameService->map->size; $x++) {
                $position = new Vector2($x, $y);
                $cell = $this->gameService->map->getCellAtPosition($position);

                $isLast = $position->matches($tailPosition);

                /** @var Imagick|null $cellImage */
                $cellImage = match ($cell->getCellType()) {
                    CellType::WALL => new Imagick($this->wallPath),
                    CellType::APPLE => new Imagick($this->applePath),
                    CellType::SNAKE_BODY_UP, CellType::SNAKE_BODY_DOWN,
                    CellType::SNAKE_BODY_LEFT, CellType::SNAKE_BODY_RIGHT
                    => new Imagick($isLast ? $this->tailPath : $this->bodyPath),
                    CellType::SNAKE_HEAD_UP, CellType::SNAKE_HEAD_LEFT,
                    CellType::SNAKE_HEAD_RIGHT, CellType::SNAKE_HEAD_DOWN
                    => new Imagick($this->headPath),
                    default => null,
                };

                $rotation = match ($cell->getCellType()) {
                    CellType::SNAKE_BODY_DOWN, CellType::SNAKE_HEAD_DOWN => 180,
                    CellType::SNAKE_BODY_LEFT, CellType::SNAKE_HEAD_LEFT => 270,
                    CellType::SNAKE_BODY_RIGHT, CellType::SNAKE_HEAD_RIGHT => 90,
                    default => 0,
                };

                if ($cellImage) {
                    $cellImage->rotateImage("white", $rotation);
                    $img->compositeImage($cellImage, Imagick::COMPOSITE_OVER,
                        $x * $cellImage->getImageWidth(), $y * $cellImage->getImageHeight());
                }
            }
        }

        return $img;
    }
}
