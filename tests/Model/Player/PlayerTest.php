<?php

namespace App\Tests\Model\Player;

use App\Model\Hole;
use App\Model\Strategy\RandomStrategy;
use App\Model\Player\Player;
use App\Model\Shot;
use Exception;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testInstantiation(): void
    {
        $name = 'Player_1';
        $placedShips = (RandomStrategy::createGridWithShips())->placedShips();
        $shots = [
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom())
        ];

        $player = new Player($name, $placedShips, $shots);
        self::assertInstanceOf(Player::class, $player);

        self::assertEquals($name, $player->name());
        self::assertEquals($placedShips, $player->placedShips());
        self::assertEquals($shots, $player->placedShots());

        self::assertCount(6, $player->placedShots());

        $shot = new Shot(Hole::createRandom());
        $player->addPlacedShot($shot);
        self::assertCount(7, $player->placedShots());
        self::assertEquals($shot, $player->placedShots()[6]);
    }

    /**
     * @throws Exception
     */
    public function testFromArrayToArray(): void
    {
        $name = 'Player_1';
        $placedShips = (RandomStrategy::createGridWithShips())->placedShips();
        $shots = [
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom()),
            new Shot(Hole::createRandom())
        ];

        $player = new Player($name, $placedShips, $shots);
        $playerArr = $player->toArray();

        self::assertEquals($playerArr, Player::fromArray($playerArr)->toArray());
    }
}
