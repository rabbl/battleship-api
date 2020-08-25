<?php

declare(strict_types=1);

namespace App\Tests\Model\Ship;

use App\Model\Ship\Battleship;
use App\Model\Ship\Carrier;
use App\Model\Ship\Cruiser;
use App\Model\Ship\Destroyer;
use App\Model\Ship\Ship;
use App\Model\Ship\Submarine;
use PHPUnit\Framework\TestCase;

class ShipTest extends TestCase
{

    public function shipsDataProvider(): array
    {
        return [
            [new Carrier(), 5, 1],
            [new Battleship(), 4, 2],
            [new Cruiser(), 3, 3],
            [new Submarine(), 3, 4],
            [new Destroyer(), 2, 5],
        ];
    }

    /**
     * @dataProvider shipsDataProvider
     *
     * @param Ship $ship
     * @param int $size
     * @param int $id
     */
    public function testShipSizesAndIds(Ship $ship, int $size, int $id): void
    {
        self::assertEquals($size, $ship->size());
        self::assertEquals($id, $ship->id());
    }
}
