<?php

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
use App\Model\Ship\Ship;
use App\Model\Ship\Submarine;
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

    public function testInstantiation(): void
    {
        $grid = new Grid();
        self::assertInstanceOf(Grid::class, $grid);

        $expectedGrid = array_fill(0, 10,
            array_fill(0, 10, 0)
        );

        self::assertEquals($expectedGrid, $grid->grid());
        self::assertEquals([], $grid->ships());
    }

    public function testPlaceShipsHorizontally(): void
    {
        $grid = new Grid();
        $grid = $grid
            ->placeShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal())
            ->placeShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal())
            ->placeShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal())
            ->placeShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal())
            ->placeShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal());

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
        self::assertCount(5, $grid->ships());
        self::assertTrue($grid->areAllShipsPlaced());
    }

    public function testPlaceShipsVertically(): void
    {
        $grid = new Grid();
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
        self::assertCount(5, $grid->ships());
    }

    public function testWhenSameShipPlacedTwiceItThrowsException(): void
    {
        $this->expectException(ShipTypeAlreadyPlacedException::class);
        $grid = new Grid();
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
        (new Grid())->placeShip($ship, $hole, $orientation);
    }

    /**
     * @dataProvider shipsOutOfBoundsDataProvider
     */
    public function testPlacingShipsOverlappingThrowsException(): void
    {
        $this->expectException(ShipOverlapsWithAnotherShipException::class);
        (new Grid())
            ->placeShip(new Carrier(), Hole::createFromLetterAndNumber('C', 3), Orientation::horizontal())
            ->placeShip(new Submarine(), Hole::createFromLetterAndNumber('C', 3), Orientation::vertical());

    }

    /**
     * @throws Exception
     */
    public function testShootingBeforeSettingAllShipsThrowsException(): void
    {
        $this->expectException(AllShipsAreNotPlacedException::class);
        (new Grid())
            ->shot(Hole::createRandom());
    }

    public function testShootingReturnsHitTypeAndShipID(): void
    {
        $grid = new Grid();
        $grid = $grid
            ->placeShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal())
            ->placeShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal())
            ->placeShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal())
            ->placeShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal())
            ->placeShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal());


        self::assertEquals([Grid::WATER, 0], $grid->shot(Hole::createFromLetterAndNumber('E', 10)));
        self::assertEquals([Grid::HIT, Destroyer::ID], $grid->shot(Hole::createFromLetterAndNumber('E', 1)));
        self::assertEquals([Grid::SUNK, Destroyer::ID], $grid->shot(Hole::createFromLetterAndNumber('E', 2)));
    }

    public function testMakeSinkAllShips(): void
    {
        $grid = new Grid();
        $grid = $grid
            ->placeShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal())
            ->placeShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal())
            ->placeShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal())
            ->placeShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal())
            ->placeShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal());

        $shots = [
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

        foreach ($shots as $key => $shot) {
            self::assertEquals($shot[1], $grid->shot($shot[0]));
            if ($key < count($shots) - 1) {
                self::assertFalse($grid->areAllShipsSunk());
            }
        }

        self::assertTrue($grid->areAllShipsSunk());
    }
}
