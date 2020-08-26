<?php

declare(strict_types=1);

namespace App\Tests\Model\Opponent\Strategy;

use App\Model\Opponent\Strategy\RandomStrategy;
use Exception;
use PHPUnit\Framework\TestCase;

class RandomStrategyTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testThatCreateGridWithShipsReturnsAGridWithAllShipsPlaced(): void
    {
        $grid = RandomStrategy::createGridWithShips();
        self::assertTrue($grid->areAllShipsPlaced());
    }
}
