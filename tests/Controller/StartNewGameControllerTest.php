<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Game;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StartNewGameControllerTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testStartNewGamePostCreatesANewGameWithRandomPlacedShips(): void
    {
        $id = Uuid::uuid4();
        $name = sprintf("player_%s", random_int(0, 1000));

        $client = static::createClient();
        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(["id" => $id->toString(), "name" => $name])
        );

        self::assertEquals(201, $client->getResponse()->getStatusCode());

        /** @var Game $game */
        $game = $client->getContainer()->get('doctrine')->getRepository(Game::class)->findOneBy(['id' => $id->toString()]);
        self::assertInstanceOf(Game::class, $game);
        self::assertEquals($name, $game->human()->name());
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
