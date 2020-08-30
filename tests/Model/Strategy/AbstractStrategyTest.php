<?php

declare(strict_types=1);

namespace App\Tests\Model\Strategy;

use App\Model\Grid;
use App\Model\Strategy\AbstractStrategy;
use Exception;
use PHPUnit\Framework\TestCase;

class AbstractStrategyTest extends TestCase
{
    /**
     * @throws Exception
     * @noinspection UnnecessaryAssertionInspection
     */
    public function testGenerateGridWithShips(): void
    {
        $grid = AbstractStrategy::createGridWithShips();
        self::assertInstanceOf(Grid::class, $grid);
        self::assertCount(5, $grid->ships());
    }
}
