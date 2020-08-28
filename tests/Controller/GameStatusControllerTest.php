<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Model\Hole;
use App\Model\Orientation;
use App\Model\Player\Player;
use App\Model\Ship\Battleship;
use App\Model\Ship\Carrier;
use App\Model\Ship\Cruiser;
use App\Model\Ship\Destroyer;
use App\Model\Ship\PlacedShip;
use App\Model\Ship\Submarine;
use App\Model\Shot;
use App\Model\Strategy\StrategyFactory;
use App\Service\GameMaster;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameStatusControllerTest extends WebTestCase
{
    private $client;
    private $gameMaster;
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->gameMaster = $this->client->getContainer()->get(GameMaster::class);

        $this->id = Uuid::uuid4();
        $this->gameMaster->createGame(
            $this->id,
            new Player(
                'Player',
                StrategyFactory::build(1)::createGridWithShips()->placedShips()
            ),
            new Player(
                'Computer',
                [
                    new PlacedShip(new Carrier(), Hole::createFromLetterAndNumber('A', 1), Orientation::horizontal()),
                    new PlacedShip(new Battleship(), Hole::createFromLetterAndNumber('B', 1), Orientation::horizontal()),
                    new PlacedShip(new Cruiser(), Hole::createFromLetterAndNumber('C', 1), Orientation::horizontal()),
                    new PlacedShip(new Submarine(), Hole::createFromLetterAndNumber('D', 1), Orientation::horizontal()),
                    new PlacedShip(new Destroyer(), Hole::createFromLetterAndNumber('E', 1), Orientation::horizontal())
                ]
            )
        );
    }

    public function testGetShotStatus(): void
    {
        $shots = [
            new Shot(Hole::createFromLetterAndNumber('F', 1)),
            new Shot(Hole::createFromLetterAndNumber('E', 1)),
            new Shot(Hole::createFromLetterAndNumber('E', 2)),
        ];

        $results = [
            [0, 0],
            [1, 5],
            [2, 5],
        ];

        foreach ($shots as $key => $shot) {
            $this->gameMaster->humanPlacesShot($this->id, $shot);

            $this->client->request(
                'GET',
                sprintf("/%s/shot/%s/%s", $this->id->toString(), $shot->hole()->letter(), $shot->hole()->number()),
                ['CONTENT_TYPE' => 'application/json']
            );

            self::assertEquals(200, $this->client->getResponse()->getStatusCode());

            $content = json_decode($this->client->getResponse()->getContent(), true);
            self::assertEquals([
                'letter' => $shot->hole()->letter(),
                'number' => $shot->hole()->number(),
                'result' => $results[$key][0],
                'shipId' => $results[$key][1]
            ], $content);
        }
    }

    /*
        TODO
        Add tests for GameStatus and Debug-Mode
    */
}
