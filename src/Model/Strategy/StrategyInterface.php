<?php

declare(strict_types=1);

namespace App\Model\Strategy;

use App\Model\ShotsCollection;
use App\Model\Grid;
use App\Model\Shot;

interface StrategyInterface
{
    public static function createGridWithShips(): Grid;

    public static function shot(Grid $grid, ShotsCollection $previousShots): Shot;
}
