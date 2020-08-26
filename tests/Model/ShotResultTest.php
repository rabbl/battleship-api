<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\Grid;
use App\Model\Hole;
use App\Model\Ship\Destroyer;
use App\Model\ShotResult;
use Exception;
use PHPUnit\Framework\TestCase;

class ShotResultTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testInstantiation(): void
    {
        $hole = Hole::createRandom();
        $result = Grid::HIT;
        $shipId = Destroyer::ID;
        $cs = new ShotResult($hole, $result, $shipId);

        self::assertInstanceOf(ShotResult::class, $cs);
        self::assertEquals($hole, $cs->hole());
        self::assertEquals($result, $cs->result());
        self::assertEquals($shipId, $cs->shipId());
    }
}
