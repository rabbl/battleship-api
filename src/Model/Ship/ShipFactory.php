<?php

declare(strict_types=1);

namespace App\Model\Ship;

use RuntimeException;

class ShipFactory
{
    public static function build($id): Ship
    {
        switch ($id) {
            case Carrier::ID:
                return new Carrier();
            case Battleship::ID:
                return new Battleship();
            case Cruiser::ID:
                return new Cruiser();
            case Submarine::ID:
                return new Submarine();
            case Destroyer::ID:
                return new Destroyer();
        }

        throw new RuntimeException('Invalid Ship id');
    }
}
