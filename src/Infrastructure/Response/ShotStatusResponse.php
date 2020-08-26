<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Entity\Game;
use App\Model\Grid;
use App\Model\Shot;
use App\Model\ShotResult;
use JsonSerializable;

class ShotStatusResponse implements JsonSerializable
{
    private $result;

    public function __construct(Game $game, Shot $shot)
    {
        $placedShips = $game->computer()->placedShips();
        $placedShots = $game->human()->placedShots();

        $grid = new Grid();
        foreach ($placedShips as $placedShip) {
            $grid = $grid->placeShip($placedShip->ship(), $placedShip->hole(), $placedShip->orientation());
        }

        foreach ($placedShots as $placedShot) {
            $result = $grid->shot($placedShot->hole());
            if ($placedShot->equals($shot)) {
                $this->result = $result;
            }
        }
    }

    public function jsonSerialize()
    {
        if (!$this->result instanceof ShotResult) {
            return [];
        }

        return $this->result;
    }
}
