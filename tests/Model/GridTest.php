<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Exception\AllShipsAreNotPlacedException;
use App\Exception\OutOfBoundsException;
use App\Exception\ShipOverlapsWithAnotherShipException;
use App\Exception\ShipTypeAlreadyPlacedException;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Orientation;
use App\Model\Ship\Battleship;
use App\Model\Ship\Carrier;
use App\Model\Ship\Cruiser;
use App\Model\Ship\Destroyer;
use App\Model\Ship\PlacedShip;
use App\Model\Ship\Ship;
use App\Model\Ship\Submarine;
use App\Model\ShotResult;
use App\Model\ShotsCollection;
use Exception;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{

    public function testStaticLettersMethodReturnsListOfOrderedLettersFromXAxis(): void
    {
        self::assertEquals(range('A', 'J'), Grid::letters());
    }

    public function testStaticNumbersMethodReturnsNumbersFromYAxis(): void
    {
        self::assertEquals(range(1, 10), Grid::numbers());
    }

    /** @noinspection UnnecessaryAssertionInspection */
    public function testInstantiation(): void
    {
        $grid = Grid::create();
        self::assertInstanceOf(Grid::class, $grid);

        $expectedGrid = array_fill(0, 10,
            array_fill(0, 10, 0)
        );

        self::assertEquals($expectedGrid, $grid->grid());
        self::assertEquals([], $grid->placedShips());
    }

    public function testPlaceShipsHorizontally(): void
    {
        $placedShips = [
            new PlacedShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal()),
            new PlacedShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal()),
            new PlacedShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal()),
            new PlacedShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal()),
            new PlacedShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal())
        ];

        $grid = Grid::replay($placedShips, ShotsCollection::create());
        self::assertEquals([
            [1, 1, 1, 1, 1, 0, 0, 0, 0, 0],
            [2, 2, 2, 2, 0, 0, 0, 0, 0, 0],
            [3, 3, 3, 0, 0, 0, 0, 0, 0, 0],
            [4, 4, 4, 0, 0, 0, 0, 0, 0, 0],
            [5, 5, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        ], $grid->grid());
        self::assertCount(5, $grid->placedShips());
        self::assertTrue($grid->areAllShipsPlaced());
    }

    public function testPlaceShipsVertically(): void
    {
        $grid = Grid::create();
        $grid = $grid->placeShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::vertical());
        $grid = $grid->placeShip(new Battleship(), Hole::createFromLetterAndNumber('A', 2), Orientation::vertical());
        $grid = $grid->placeShip(new Cruiser(), Hole::createFromLetterAndNumber('A', 3), Orientation::vertical());
        $grid = $grid->placeShip(new Submarine(), Hole::createFromLetterAndNumber('A', 4), Orientation::vertical());
        $grid = $grid->placeShip(new Destroyer(), Hole::createFromLetterAndNumber('A', 5), Orientation::vertical());
        self::assertEquals([
            [1, 2, 3, 4, 5, 0, 0, 0, 0, 0],
            [1, 2, 3, 4, 5, 0, 0, 0, 0, 0],
            [1, 2, 3, 4, 0, 0, 0, 0, 0, 0],
            [1, 2, 0, 0, 0, 0, 0, 0, 0, 0],
            [1, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        ], $grid->grid());
        self::assertCount(5, $grid->placedShips());
    }

    public function testWhenSameShipPlacedTwiceItThrowsException(): void
    {
        $this->expectException(ShipTypeAlreadyPlacedException::class);
        $grid = Grid::create();
        $grid = $grid->placeShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal());
        $grid->placeShip(new Carrier(), Hole::createFromLetterAndNumber('B', 2), Orientation::horizontal());
    }

    public function shipsOutOfBoundsDataProvider(): array
    {
        return [
            [Hole::createFromLetterAndNumber('A', 7), new Carrier(), Orientation::horizontal()],
            [Hole::createFromLetterAndNumber('A', 8), new Battleship(), Orientation::horizontal()],
            [Hole::createFromLetterAndNumber('A', 9), new Cruiser(), Orientation::horizontal()],
            [Hole::createFromLetterAndNumber('A', 9), new Submarine(), Orientation::horizontal()],
            [Hole::createFromLetterAndNumber('A', 10), new Destroyer(), Orientation::horizontal()],
            [Hole::createFromLetterAndNumber('G', 1), new Carrier(), Orientation::vertical()],
            [Hole::createFromLetterAndNumber('H', 1), new Battleship(), Orientation::vertical()],
            [Hole::createFromLetterAndNumber('I', 1), new Cruiser(), Orientation::vertical()],
            [Hole::createFromLetterAndNumber('I', 1), new Submarine(), Orientation::vertical()],
            [Hole::createFromLetterAndNumber('J', 1), new Destroyer(), Orientation::vertical()],
        ];
    }

    /**
     * @dataProvider shipsOutOfBoundsDataProvider
     * @param Hole $hole
     * @param Ship $ship
     * @param Orientation $orientation
     */
    public function testPlacingShipsOutOfBoundsThrowsException(Hole $hole, Ship $ship, Orientation $orientation): void
    {
        $this->expectException(OutOfBoundsException::class);
        Grid::create()->placeShip($ship, $hole, $orientation);
    }

    /**
     * @dataProvider shipsOutOfBoundsDataProvider
     */
    public function testPlacingShipsOverlappingThrowsException(): void
    {
        $this->expectException(ShipOverlapsWithAnotherShipException::class);
        Grid::create()
            ->placeShip(new Carrier(), Hole::createFromLetterAndNumber('C', 3), Orientation::horizontal())
            ->placeShip(new Submarine(), Hole::createFromLetterAndNumber('C', 3), Orientation::vertical());

    }

    /**
     * @throws Exception
     */
    public function testShootingBeforeSettingAllShipsThrowsException(): void
    {
        $this->expectException(AllShipsAreNotPlacedException::class);
        Grid::create()
            ->shot(Hole::createRandom());
    }

    public function testShootingReturnsHitTypeAndShipID(): void
    {
        $grid = Grid::create();
        $grid = $grid
            ->placeShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal())
            ->placeShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal())
            ->placeShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal())
            ->placeShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal())
            ->placeShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal());


        $hole = Hole::createFromLetterAndNumber('E', 10);
        self::assertEquals(new ShotResult($hole, Grid::WATER), $grid->shot($hole));

        $hole = Hole::createFromLetterAndNumber('E', 1);
        self::assertEquals(new ShotResult($hole, Grid::HIT, Destroyer::ID), $grid->shot($hole));

        $hole = Hole::createFromLetterAndNumber('E', 2);
        self::assertEquals(new ShotResult($hole, Grid::SUNK, Destroyer::ID), $grid->shot($hole));
    }

    public function testMakeSinkAllShips(): void
    {
        $grid = Grid::create();
        $grid = $grid
            ->placeShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal())
            ->placeShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal())
            ->placeShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal())
            ->placeShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal())
            ->placeShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal());

        $holesToShotWithResults = [
            [Hole::createFromLetterAndNumber('A', 1), [Grid::HIT, Carrier::ID]],
            [Hole::createFromLetterAndNumber('A', 2), [Grid::HIT, Carrier::ID]],
            [Hole::createFromLetterAndNumber('A', 3), [Grid::HIT, Carrier::ID]],
            [Hole::createFromLetterAndNumber('A', 4), [Grid::HIT, Carrier::ID]],
            [Hole::createFromLetterAndNumber('A', 5), [Grid::SUNK, Carrier::ID]],
            [Hole::createFromLetterAndNumber('B', 1), [Grid::HIT, Battleship::ID]],
            [Hole::createFromLetterAndNumber('B', 2), [Grid::HIT, Battleship::ID]],
            [Hole::createFromLetterAndNumber('B', 3), [Grid::HIT, Battleship::ID]],
            [Hole::createFromLetterAndNumber('B', 4), [Grid::SUNK, Battleship::ID]],
            [Hole::createFromLetterAndNumber('C', 1), [Grid::HIT, Cruiser::ID]],
            [Hole::createFromLetterAndNumber('C', 2), [Grid::HIT, Cruiser::ID]],
            [Hole::createFromLetterAndNumber('C', 3), [Grid::SUNK, Cruiser::ID]],
            [Hole::createFromLetterAndNumber('D', 1), [Grid::HIT, Submarine::ID]],
            [Hole::createFromLetterAndNumber('D', 2), [Grid::HIT, Submarine::ID]],
            [Hole::createFromLetterAndNumber('D', 3), [Grid::SUNK, Submarine::ID]],
            [Hole::createFromLetterAndNumber('E', 1), [Grid::HIT, Destroyer::ID]],
            [Hole::createFromLetterAndNumber('E', 2), [Grid::SUNK, Destroyer::ID]]
        ];

        foreach ($holesToShotWithResults as $key => $holeWithResult) {
            self::assertEquals(new ShotResult($holeWithResult[0], $holeWithResult[1][0], $holeWithResult[1][1]), $grid->shot($holeWithResult[0]));
            if ($key < count($holesToShotWithResults) - 1) {
                self::assertFalse($grid->areAllShipsSunk());
            }
        }

        self::assertTrue($grid->areAllShipsSunk());
    }

    public function testReplay(): void
    {
        $placedShips = [
            new PlacedShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal()),
            new PlacedShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal()),
            new PlacedShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal()),
            new PlacedShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal()),
            new PlacedShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal())
        ];

        $grid = Grid::replay($placedShips, ShotsCollection::create());
        self::assertCount(5, $grid->placedShips());
        self::assertEqualsCanonicalizing($placedShips, $grid->placedShips());
    }
}
