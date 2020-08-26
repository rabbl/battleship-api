<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Model\Player\Player;
use App\Model\Strategy\StrategyFactory;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\GameMaster;

class DeleteGameControllerTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testDeleteGameDeletesAnExistingGame(): void
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

        $client->request(
            'DELETE',
            sprintf('/%s', $id->toString())
        );

        self::assertEquals(201, $client->getResponse()->getStatusCode());
        self::assertFalse($gameMaster->gameIdExists($id));
    }

    /*
     * Todo
     *
     * Add tests with:
     *
     * * Not existing Game-id (201)
     */
}
