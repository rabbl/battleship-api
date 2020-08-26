<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Model\Hole;
use App\Model\Player\Player;
use App\Model\Strategy\StrategyFactory;
use App\Service\GameMaster;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaceShotControllerTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testPlaceShot(): void
    {
        $client = static::createClient();
        $gameMaster = $client->getContainer()->get(GameMaster::class);

        $id = Uuid::uuid4();
        $gameMaster->createGame(
            $id,
            new Player(
                'Player',
                StrategyFactory::build(1)::createGridWithShips()->placedShips()
            ),
            new Player(
                'Computer',
                StrategyFactory::build(1)::createGridWithShips()->placedShips()
            )
        );

        self::assertTrue($gameMaster->gameIdExists($id));

        $hole = Hole::createRandom();
        $client->request(
            'POST',
            sprintf("/%s/shot", $id->toString()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "letter" => $hole->letter(),
                "number" => $hole->number()
            ])
        );

        self::assertEquals(201, $client->getResponse()->getStatusCode());

        $game = $gameMaster->loadGame($id);
        self::assertEquals($hole->toArray(), $game->human()->placedShots()[0]->hole()->toArray());
    }

    /*
     * Todo
     *
     * Add tests with:
     *
     * * Request without content type (322)
     * * Invalid JSON (322)
     * * Invalid content (see schema) (322)
     * * Existing Game-id (409)
     */
}
