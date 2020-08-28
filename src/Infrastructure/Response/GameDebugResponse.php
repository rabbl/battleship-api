<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Entity\Game;
use App\Model\Grid;
use App\Model\Ship\PlacedShip;
use App\Model\ShotsCollection;
use JsonSerializable;

class GameDebugResponse implements JsonSerializable
{
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function buildGrid(array $placedShips, array $shots): Grid
    {
        $grid = new Grid();
        /** @var PlacedShip $placedShip */
        foreach ($placedShips as $placedShip) {
            $grid = $grid->placeShip($placedShip->ship(), $placedShip->hole(), $placedShip->orientation());
        }

        foreach ($shots as $shot) {
            $grid->shot($shot->hole());
        }

        return $grid;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->game->id()->toString(),
            'human' => $this->game->human()->toArray(),
            'computer' => $this->game->computer()->toArray(), // Todo, remove this line
            'humanGrid' => $this->buildGrid($this->game->human()->placedShips(), $this->game->computer()->placedShots())->render(),
            'computerGrid' => $this->buildGrid($this->game->computer()->placedShips(), $this->game->human()->placedShots())->render()
        ];
    }
}
