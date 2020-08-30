<?php

declare(strict_types=1);

namespace App\Tests\Model\Strategy;

use App\Model\Strategy\FullRandomStrategy;
use PHPUnit\Framework\TestCase;

class FullRandomStrategyTest extends TestCase
{
    public function testInstantiation(): void
    {
        $strategy = new FullRandomStrategy();
        self::assertInstanceOf(FullRandomStrategy::class, $strategy);
    }

    // Todo: complete tests
}
