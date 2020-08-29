<?php

declare(strict_types=1);

namespace App\Model\Strategy;

use App\Model\ShotsCollection;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Shot;
use Exception;

class RandomStrategy extends FullRandomStrategy
{
    public const ID = 2;

    /**
     * This strategy creates random shots
     * but never throws twice to the same location
     *
     * @param Grid $grid
     * @param ShotsCollection $previousShots
     * @return Shot
     * @throws Exception
     */
    public static function shot(Grid $grid, ShotsCollection $previousShots): Shot
    {
        $holesWithoutShot = [];
        foreach (Grid::letters() as $letter) {
            foreach (Grid::numbers() as $number) {
                $hole = Hole::createFromLetterAndNumber($letter, $number);
                $holeAlreadyShot = false;
                /** @var Shot $previousShot */
                foreach ($previousShots->items() as $previousShot) {
                    if ($previousShot->hole()->equals($hole)) {
                        $holeAlreadyShot = true;
                    }
                }

                if ($holeAlreadyShot === false) {
                    $holesWithoutShot[] = $hole;
                }
            }
        }

        $randomHole = $holesWithoutShot[array_rand($holesWithoutShot, 1)];

        return $grid->shot($randomHole);
    }
}
