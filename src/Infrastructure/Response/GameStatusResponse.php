<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Entity\Game;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\ShotsCollection;
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
     *
     * For each field in the map returns an array with two integers.
     *
     * Example: [2, 1]
     *
     * First number is the shipId or 0 for water
     * Second is the shot flag, 0 for no shot, 1 for shot
     *
     * Examples:
     *
     * [0, 0]: Water with no shot
     * [1, 0]: Ship with id 1 and no shot from opponent
     * [2, 1]: Ship with id 2 anf shot from opponent
     * @return array
     */
    public function renderOceanView(): array
    {
        $human = $this->game->human();
        $computer = $this->game->computer();

        $grid = Grid::replay($human->placedShips(), ShotsCollection::create());

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
     * Returns a 2D-array with shots done and result.
     * Values are:
     *
     * Examples:
     *
     * NULL: for unknown
     * [0, 0]: for water
     * [1, 2]: for hit ship with id 2
     * [2, 2]: for sink ship with id 2
     *
     * @return array
     */
    public function renderTargetView(): array
    {
        $human = $this->game->human();
        $computer = $this->game->computer();

        $grid = Grid::replay($computer->placedShips(), ShotsCollection::create());

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

        $grid = Grid::replay($computer->placedShips(), ShotsCollection::fromArray($human->placedShots()));

        $humanScore = $grid->calculateOpponentsScore();

        if ($grid->areAllShipsSunk()) {
            $message = sprintf("The winner is the human! Good game %s!", $human->name());
        }

        $grid = Grid::replay($human->placedShips(), ShotsCollection::fromArray($computer->placedShots()));
        $computerScore = $grid->calculateOpponentsScore();

        if ($grid->areAllShipsSunk()) {
            $message = sprintf("The winner is the machine! You loose %s!", $human->name());
        }

        return [
            'human' => $humanScore,
            'computer' => $computerScore,
            'message' => $message
        ];
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->game->id()->toString(),
            'human' => $this->game->human()->toArray(),
            'oceanView' => $this->renderOceanView(),
            'targetView' => $this->renderTargetView(),
            'score' => $this->calculateScores()
        ];
    }
}
