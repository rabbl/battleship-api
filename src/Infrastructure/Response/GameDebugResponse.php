<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Entity\Game;
use App\Model\Grid;
use App\Model\ShotsCollection;
use JsonSerializable;

class GameDebugResponse implements JsonSerializable
{
    private Game $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function buildGrid(array $placedShips, array $shots): Grid
    {
        return Grid::replay($placedShips, ShotsCollection::fromArray($shots));
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->game->id()->toString(),
            'human' => $this->game->human()->toArray(),
            'computer' => $this->game->computer()->toArray(),
            'humanGrid' => $this->buildGrid($this->game->human()->placedShips(), $this->game->computer()->placedShots())->render(),
            'computerGrid' => $this->buildGrid($this->game->computer()->placedShips(), $this->game->human()->placedShots())->render()
        ];
    }
}
