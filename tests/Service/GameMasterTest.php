<?php

namespace App\Tests\Service;

use App\Service\GameMaster;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GameMasterTest extends TestCase
{
    public function testInstantiation(): void
    {
        /** @var EntityManagerInterface | MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $gameMaster = new GameMaster($entityManager);
        self::assertInstanceOf(GameMaster::class, $gameMaster);
    }

    // Todo: Complete Tests
}
