<?php

declare(strict_types=1);

namespace App\Tests\Model\Strategy;

use App\Model\Strategy\HuntTargetStrategy;
use PHPUnit\Framework\TestCase;

class HuntTargetStrategyTest extends TestCase
{
    public function testInstantiation(): void
    {
        $strategy = new HuntTargetStrategy();
        self::assertInstanceOf(HuntTargetStrategy::class, $strategy);
    }

    // Todo: complete tests
}
