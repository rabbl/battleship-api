<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\AllShipsAreNotPlacedException;
use App\Exception\OutOfBoundsException;
use App\Exception\ShipOverlapsWithAnotherShipException;
use App\Exception\ShipTypeAlreadyPlacedException;
use App\Model\Ship\Ship;

/**
 * Inspired from https://github.com/restgames/battleship-php
 * https://github.com/carlosbuenosvinos
 */
class Grid
{
    private const NUMBER_OF_SHIPS = 5;

    public const START_LETTER = 'A';
    public const END_LETTER = 'J';

    public const START_NUMBER = 1;
    public const END_NUMBER = 10;

    public const WATER = 0;
    public const HIT = 1;
    public const SUNK = 2;

    private $grid;
    private $ships;

    public static function letters(): array
    {
        return range(self::START_LETTER, self::END_LETTER);
    }

    public static function numbers(): array
    {
        return range(self::START_NUMBER, self::END_NUMBER);
    }

    public static function fromGrid(Grid $grid): Grid
    {
        $self = new self();
        $self->grid = $grid->grid;
        $self->ships = $grid->ships;
        return $self;
    }

    public function __construct()
    {
        $this->ships = [];
        $this->grid = [];

        foreach (static::letters() as $i => $letter) {
            foreach (static::numbers() as $j => $number) {
                $this->grid[$i][$j] = static::WATER;
            }
        }
    }

    public function placeShip(Ship $ship, Hole $hole, Orientation $orientation): Grid
    {
        $grid = static::fromGrid($this);
        $shipId = $ship->id();

        if (isset($grid->ships[$shipId])) {
            throw new ShipTypeAlreadyPlacedException(sprintf('Ship from type %s already placed.', $shipId));
        }

        for ($i = 0; $i < $ship->size(); $i++) {
            if ($orientation->isVertical()) {
                $x = Hole::convertLetterToNumber($hole->letter()) + $i - 1;
                $y = $hole->number() - 1;
            }

            if ($orientation->isHorizontal()) {
                $x = Hole::convertLetterToNumber($hole->letter()) - 1;
                $y = $hole->number() + $i - 1;
            }

            if (!isset($x, $y)) {
                throw new OutOfBoundsException('Ship somehow does not fit into the grid.');
            }

            if (!isset($grid->grid[$x][$y])) {
                throw new OutOfBoundsException('Ship does not fit into the grid with such a hole and position');
            }

            if ($grid->grid[$x][$y] > 0) {
                throw new ShipOverlapsWithAnotherShipException('Ship overlaps with another one.');
            }

            $grid->grid[$x][$y] = $ship->id();
            $grid->ships[$shipId] = $ship;
        }

        return $grid;
    }

    public function areAllShipsPlaced(): bool
    {
        return count($this->ships()) === self::NUMBER_OF_SHIPS;
    }

    public function areAllShipsSunk(): bool
    {
        $allShipsAreSunk = true;
        foreach ($this->ships as $ship) {
            $allShipsAreSunk = $allShipsAreSunk && $this->isShipSunk($ship);
        }

        return $allShipsAreSunk;
    }

    private function isShipSunk(Ship $ship): bool
    {
        $size = $ship->size();
        $count = 0;

        foreach ($this->grid as $y => $letter) {
            foreach ($letter as $x => $number) {
                if ($this->grid[$y][$x] === -$ship->id()) {
                    ++$count;
                }
            }
        }

        return $count === $size;
    }

    public function shot(Hole $hole): array
    {
        if (!$this->areAllShipsPlaced()) {
            throw new AllShipsAreNotPlacedException('All ships must be placed before shooting');
        }

        $y = Hole::convertLetterToNumber($hole->letter()) - 1;
        $x = $hole->number() - 1;
        $shipId = $this->grid[$y][$x];
        if ($shipId !== 0) {
            $this->grid[$y][$x] = -abs($this->grid[$y][$x]);

            if ($this->isShipSunk($this->ships[abs($shipId)])) {
                return [self::SUNK, $shipId];
            }

            return [self::HIT, $shipId];
        }

        return [self::WATER, 0];
    }

    public function grid(): array
    {
        return $this->grid;
    }

    public function ships(): array
    {
        return $this->ships;
    }
}