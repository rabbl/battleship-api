<?php

declare(strict_types=1);

namespace App\Tests\Model\Strategy;

use App\Model\Strategy\RandomStrategy;
use PHPUnit\Framework\TestCase;

class RandomStrategyTest extends TestCase
{
    public function testInstantiation(): void
    {
        $strategy = new RandomStrategy();
        self::assertInstanceOf(RandomStrategy::class, $strategy);
    }

    // Todo: complete tests
}
