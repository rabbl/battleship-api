<?php

namespace App\Tests\Entity;

use App\Entity\Game;
use App\Model\Strategy\RandomStrategy;
use App\Model\Player\Player;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameTest extends KernelTestCase
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @throws Exception
     * @noinspection UnnecessaryAssertionInspection
     */
    public function testInstantiate(): void
    {
        $id = Uuid::uuid4();
        $human = new Player(
            'Player_1',
            (RandomStrategy::createGridWithShips())->placedShips()
        );
        $computer = new Player(
            'Player_2',
            (RandomStrategy::createGridWithShips())->placedShips()
        );

        $game = Game::createNew($id, $human, $computer);
        self::assertInstanceOf(Game::class, $game);

        self::assertEquals($id, $game->id());
        self::assertEquals($human->toArray(), $game->human()->toArray());
        self::assertEquals($computer->toArray(), $game->computer()->toArray());
        self::assertInstanceOf(DateTime::class, $game->createdAt());
        self::assertInstanceOf(DateTime::class, $game->updatedAt());
    }

    /**
     * @throws ORMException
     * @throws Exception
     */
    public function testDbInteraction(): void
    {
        $id = Uuid::uuid4();
        $human = new Player(
            'Player_1',
            (RandomStrategy::createGridWithShips())->placedShips()
        );
        $computer = new Player(
            'Player_2',
            (RandomStrategy::createGridWithShips())->placedShips()
        );

        $game = Game::createNew($id, $human, $computer);
        $this->entityManager->persist($game);
        $this->entityManager->flush();

        $storedGame = $this->entityManager->getRepository(Game::class)->findOneBy(['id' => $id]);
        self::assertInstanceOf(Game::class, $storedGame);
        self::assertEquals($game, $storedGame);
    }
}
