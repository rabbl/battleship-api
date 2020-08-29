<?php

declare(strict_types=1);

namespace App\Model\Strategy;

class StrategyFactory
{
    public static function build($id): StrategyInterface
    {
        if ($id === 2) {
            return new RandomStrategy();
        }

        return new FullRandomStrategy();
    }
}
