<?php

declare(strict_types=1);

namespace App\Model\Opponent\Strategy;

use App\Exception\OutOfBoundsException;
use App\Exception\ShipOverlapsWithAnotherShipException;
use App\Model\ShotsCollection;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Orientation;
use App\Model\Ship\Battleship;
use App\Model\Ship\Carrier;
use App\Model\Ship\Cruiser;
use App\Model\Ship\Destroyer;
use App\Model\Ship\Submarine;
use App\Model\Shot;
use Exception;

class RandomStrategy implements StrategyInterface
{
    /**
     * @return Grid
     * @throws Exception
     */
    public static function createGridWithShips(): Grid
    {
        $grid = new Grid();

        $ships = [];
        $ships[] = new Carrier();
        $ships[] = new Battleship();
        $ships[] = new Cruiser();
        $ships[] = new Submarine();
        $ships[] = new Destroyer();

        foreach ($ships as $ship) {
            $attempts = 0;
            do {
                try {
                    $grid = $grid->placeShip($ship, Hole::createRandom(), Orientation::createRandom());
                    break;
                } catch (OutOfBoundsException $e) {
                    $attempts++;
                    continue;
                } catch (ShipOverlapsWithAnotherShipException $e) {
                    $attempts++;
                    continue;
                }
            } while ($attempts < 10);
        }

        return $grid;
    }

    /**
     * @param Grid $grid
     * @param ShotsCollection $previousShots
     * @return Shot
     * @throws Exception
     */
    public static function shot(Grid $grid, ShotsCollection $previousShots): Shot
    {
        return $grid->shot(Hole::createRandom());
    }
}
