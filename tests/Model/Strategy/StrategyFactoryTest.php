<?php

namespace App\Tests\Model\Strategy;

use App\Model\Strategy\FullRandomStrategy;
use App\Model\Strategy\HuntTargetStrategy;
use App\Model\Strategy\RandomStrategy;
use App\Model\Strategy\StrategyFactory;
use App\Model\Strategy\StrategyInterface;
use PHPUnit\Framework\TestCase;

class StrategyFactoryTest extends TestCase
{

    public function createDataProvider(): array
    {
        return [
            [0, new FullRandomStrategy()],
            [1, new FullRandomStrategy()],
            [2, new RandomStrategy()],
            [3, new HuntTargetStrategy()],
        ];
    }

    /**
     * @dataProvider createDataProvider()
     * @param int $id
     * @param StrategyInterface $s
     */
    public function testCreate(int $id, StrategyInterface $s): void
    {
        $strategy = StrategyFactory::build($id);
        self::assertEquals($s, $strategy);
    }
}
