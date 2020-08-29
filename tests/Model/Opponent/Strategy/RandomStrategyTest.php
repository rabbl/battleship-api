<?php

declare(strict_types=1);

namespace App\Tests\Model\Opponent\Strategy;

use App\Model\Strategy\FullRandomStrategy;
use Exception;
use PHPUnit\Framework\TestCase;

class RandomStrategyTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testThatCreateGridWithShipsReturnsAGridWithAllShipsPlaced(): void
    {
        $grid = FullRandomStrategy::createGridWithShips();
        self::assertTrue($grid->areAllShipsPlaced());
    }
}
