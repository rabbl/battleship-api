<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Entity\Game;
use App\Model\Grid;
use App\Model\Hole;
use JsonSerializable;

class GameStatusResponse implements JsonSerializable
{
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Renders a map with own ships placed and the shots from opponent.
     * @return array
     */
    public function renderOceanView(): array
    {
        $human = $this->game->human();
        $computer = $this->game->computer();

        $grid = new Grid();
        foreach ($human->placedShips() as $placedShip) {
            $grid = $grid->placeShip($placedShip->ship(), $placedShip->hole(), $placedShip->orientation());
        }

        $oceanView = [];
        foreach ($grid->grid() as $letter => $row) {
            $oceanView[$letter] = [];
            foreach ($row as $number => $value) {
                $oceanView[$letter][$number] = [$value, 0];
            }
        }

        foreach ($computer->placedShots() as $shot) {
            $y = Hole::convertLetterToNumber($shot->hole()->letter()) - 1;
            $x = $shot->hole()->number() - 1;
            $oceanView[$y][$x][1] = 1;
        }

        return $oceanView;
    }

    /**
     * Renders a 2D-map with shots done and result.
     * @return array
     */
    public function renderTargetView(): array
    {
        $human = $this->game->human();
        $computer = $this->game->computer();

        $grid = new Grid();
        foreach ($computer->placedShips() as $placedShip) {
            $grid = $grid->placeShip($placedShip->ship(), $placedShip->hole(), $placedShip->orientation());
        }

        $targetView = [];
        foreach (grid::letters() as $letter => $lValue) {
            $targetView[$letter] = [];
            foreach (grid::numbers() as $number => $nValue) {
                $targetView[$letter][$number] = null;
            }
        }

        foreach ($human->placedShots() as $shot) {
            $shotResult = $grid->shot($shot->hole());
            $y = Hole::convertLetterToNumber($shot->hole()->letter()) - 1;
            $x = $shot->hole()->number() - 1;
            $targetView[$y][$x] = [$shotResult->result(), $shotResult->shipId()];
        }

        return $targetView;
    }

    public function calculateScores(): array
    {
        $human = $this->game->human();
        $computer = $this->game->computer();
        $message = '';

        $grid = new Grid();
        foreach ($computer->placedShips() as $placedShip) {
            $grid = $grid->placeShip($placedShip->ship(), $placedShip->hole(), $placedShip->orientation());
        }

        $humanPoints = 0;
        foreach ($human->placedShots() as $shot) {
            $shotResult = $grid->shot($shot->hole());
            $humanPoints += $shotResult->result();
        }

        if ($grid->areAllShipsSunk()) {
            $message = sprintf("The is the human! Good game %s!", $human->name());
        }

        $grid = new Grid();
        foreach ($human->placedShips() as $placedShip) {
            $grid = $grid->placeShip($placedShip->ship(), $placedShip->hole(), $placedShip->orientation());
        }

        $computerPoints = 0;
        foreach ($computer->placedShots() as $shot) {
            $shotResult = $grid->shot($shot->hole());
            $computerPoints += $shotResult->result();
        }

        if ($grid->areAllShipsSunk()) {
            $message = sprintf("The winner is the machine! You loose %s!", $human->name());
        }

        return [
            'human' => $humanPoints,
            'computer' => $computerPoints,
            'message' => $message
        ];
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->game->id()->toString(),
            'human' => $this->game->human()->toArray(),
            'computer' => $this->game->computer()->toArray(), // Todo, remove this line
            'oceanView' => $this->renderOceanView(),
            'targetView' => $this->renderTargetView(),
            'score' => $this->calculateScores()
        ];
    }
}
