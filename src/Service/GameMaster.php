<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Game;
use App\Exception\GameNotFoundException;
use App\Model\Grid;
use App\Model\ShotsCollection;
use App\Model\Player\Player;
use App\Model\Shot;
use App\Model\ShotResult;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class GameMaster
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function gameIdExists(UuidInterface $id): bool
    {
        return $this->loadGame($id) instanceof Game;
    }

    public function loadGame(UuidInterface $id): ?Game
    {
        return $this->entityManager->getRepository(Game::class)->findOneBy(['id' => $id]);
    }

    public function deleteGame(UuidInterface $id): void
    {
        $game = $this->loadGame($id);
        if (!$game instanceof Game) {
            return;
        }

        $this->entityManager->remove($game);
        $this->entityManager->flush();
    }

    private function saveGame(Game $game): void
    {
        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }

    public function createGame(UuidInterface $id, Player $human, Player $computer): Game
    {
        $game = Game::createNew(
            $id,
            $human,
            $computer
        );

        $this->entityManager->persist($game);
        $this->entityManager->flush();
        return $game;
    }

    public function humanPlacesShot(UuidInterface $id, Shot $shot): ShotResult
    {
        $game = $this->loadGame($id);

        if (!$game instanceof Game) {
            throw new GameNotFoundException(sprintf('Game with id %s not found.', $id->toString()));
        }

        $human = $game->human();
        $computer = $game->computer();

        /* Load the computers game board */
        $grid = Grid::replay($computer->placedShips(), ShotsCollection::fromArray($human->placedShots()));
        $response = $grid->shot($shot->hole());

        $human->addPlacedShot($shot);
        $game->setHuman($human);
        $this->saveGame($game);

        return $response;
    }

    public function computerPlacesShot(UuidInterface $id): ShotResult
    {
        $game = $this->loadGame($id);

        if (!$game instanceof Game) {
            throw new GameNotFoundException(sprintf('Game with id %s not found.', $id->toString()));
        }

        $human = $game->human();
        $computer = $game->computer();

        /* Load the computers game board */
        $grid = Grid::replay($human->placedShips(), ShotsCollection::fromArray($computer->placedShots()));

        $strategy = $computer->strategy();
        $shots = ShotsCollection::fromArray($computer->placedShots());
        $shot = $strategy::shot($grid, $shots);

        $response = $grid->shot($shot->hole());

        $computer->addPlacedShot($shot);
        $game->setComputer($computer);
        $this->saveGame($game);

        return $response;
    }
}
