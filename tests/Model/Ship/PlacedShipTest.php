<?php

namespace App\Tests\Model\Ship;

use App\Model\Hole;
use App\Model\Orientation;
use App\Model\Ship\PlacedShip;
use App\Model\Ship\ShipFactory;
use Exception;
use PHPUnit\Framework\TestCase;

class PlacedShipTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testInstantiation(): void
    {
        $ship = ShipFactory::build(1);
        $hole = Hole::createRandom();
        $orientation = Orientation::createRandom();

        $placedShip = new PlacedShip($ship, $hole, $orientation);
        self::assertInstanceOf(PlacedShip::class, $placedShip);

        self::assertEquals($ship, $placedShip->ship());
        self::assertEquals($ship->id(), $placedShip->id());
        self::assertEquals($ship->size(), $placedShip->size());
        self::assertEquals($hole, $placedShip->hole());
        self::assertEquals($orientation, $placedShip->orientation());
    }

    /**
     * @throws Exception
     */
    public function testFromArrayToArray(): void
    {
        $ship = ShipFactory::build(1);
        $hole = Hole::createRandom();
        $orientation = Orientation::createRandom();

        $placedShip = new PlacedShip($ship, $hole, $orientation);
        $arr = $placedShip->toArray();
        self::assertEquals($placedShip, PlacedShip::fromArray($arr));
    }
}
