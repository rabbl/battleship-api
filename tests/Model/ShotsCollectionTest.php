<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\ShotResult;
use App\Model\ShotsCollection;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Ship\Destroyer;
use Exception;
use PHPUnit\Framework\TestCase;

class ShotsCollectionTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testAdd(): void
    {
        $hole = Hole::createRandom();
        $result = Grid::HIT;
        $shipId = Destroyer::ID;
        $shot = new ShotResult($hole, $result, $shipId);

        $csc = new ShotsCollection();
        $csc->add($shot);

        self::assertEquals([$shot], $csc->items());
    }
}
