<?php

declare(strict_types=1);

namespace App\Model\Strategy;

use App\Model\Grid;
use App\Model\Hole;
use App\Model\Shot;

class RandomStrategy extends FullRandomStrategy
{
    public const ID = 2;

    public function calculateShot(): Shot
    {
        return $this->calculateUniqueRandomShot();
    }

    protected function calculateUniqueRandomShot(): Shot
    {
        $holesWithoutShot = [];
        foreach (Grid::letters() as $letter) {
            foreach (Grid::numbers() as $number) {
                $hole = Hole::createFromLetterAndNumber($letter, $number);
                if ($this->isHoleAlreadyShot($hole) === false) {
                    $holesWithoutShot[] = $hole;
                }
            }
        }

        return new Shot($holesWithoutShot[array_rand($holesWithoutShot, 1)]);
    }

    protected function isHoleAlreadyShot(Hole $hole): bool
    {
        $holeAlreadyShot = false;
        /** @var Shot $previousShot */
        foreach ($this->previousShots->items() as $previousShot) {
            if ($previousShot->hole()->equals($hole)) {
                $holeAlreadyShot = true;
            }
        }

        return $holeAlreadyShot;
    }
}
