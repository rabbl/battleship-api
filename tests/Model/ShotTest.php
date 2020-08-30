<?php

namespace App\Tests\Model;

use App\Model\Hole;
use App\Model\Shot;
use Exception;
use PHPUnit\Framework\TestCase;

class ShotTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testInstantiation(): void
    {
        $shot = new Shot(Hole::createRandom());
        self::assertInstanceOf(Shot::class, $shot);
    }

    // Todo: complete tests

}
