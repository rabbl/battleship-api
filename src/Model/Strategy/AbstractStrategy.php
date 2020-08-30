<?php

declare(strict_types=1);

namespace App\Model\Strategy;

use App\Exception\OutOfBoundsException;
use App\Exception\ShipOverlapsWithAnotherShipException;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Orientation;
use App\Model\Ship\Battleship;
use App\Model\Ship\Carrier;
use App\Model\Ship\Cruiser;
use App\Model\Ship\Destroyer;
use App\Model\Ship\Submarine;
use App\Model\Shot;
use App\Model\ShotsCollection;
use Exception;

abstract class AbstractStrategy implements StrategyInterface
{
    /** @var Grid */
    protected Grid $grid;

    /** @var ShotsCollection */
    protected ShotsCollection $previousShots;

    protected static function create(Grid $grid, ShotsCollection $previousShots): self
    {
        $self = new static();
        $self->grid = $grid;
        $self->previousShots = $previousShots;

        return $self;
    }

    /**
     * This strategy creates random shots
     *
     * @return Grid
     * @throws Exception
     */
    public static function createGridWithShips(): Grid
    {
        $grid = Grid::create();

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
            } while ($attempts < 100);
        }

        return $grid;
    }

    /**
     * @param Hole $hole
     * @return bool
     */
    protected function isHoleAlreadyShot(Hole $hole): bool
    {
        $holeAlreadyShot = false;
        /** @var Shot $previousShot */
        foreach ($this->previousShots->items() as $previousShot) {
            if ($previousShot->hole()->equals($hole)) {
                $holeAlreadyShot = true;
            }
        }

        return $holeAlreadyShot;
    }
}
