<?php

declare(strict_types=1);

namespace App\Model\Strategy;

use App\Model\ShotsCollection;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Shot;
use Exception;

/**
 * Class FullRandomStrategy
 * @package App\Model\Strategy
 *
 * Creates a grid with random ships
 * Calculates a fully random shot
 */
class FullRandomStrategy extends AbstractStrategy
{
    public const ID = 1;

    /**
     * @param Grid $grid
     * @param ShotsCollection $previousShots
     * @return Shot
     * @throws Exception
     */
    public static function shot(Grid $grid, ShotsCollection $previousShots): Shot
    {
        return static::create($grid, $previousShots)->calculateShot();
    }

    /**
     * @return Shot
     * @throws Exception
     */
    public function calculateShot(): Shot
    {
        return new Shot(Hole::createRandom());
    }
}
