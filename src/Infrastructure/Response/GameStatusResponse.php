<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Entity\Game;
use JsonSerializable;

class GameStatusResponse implements JsonSerializable
{
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->game->id()->toString(),
            'human' => $this->game->human()->toArray(),
            'computer' => $this->game->computer()->toArray()
        ];
    }
}
