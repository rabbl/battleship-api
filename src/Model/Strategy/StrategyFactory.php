<?php

declare(strict_types=1);

namespace App\Model\Strategy;

class StrategyFactory
{
    public static function build($id): StrategyInterface
    {
        return new RandomStrategy();
    }
}
