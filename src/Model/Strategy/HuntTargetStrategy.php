<?php

declare(strict_types=1);

namespace App\Model\Strategy;

use App\Model\Ship\PlacedShip;
use App\Model\ShotResult;
use App\Model\Grid;
use App\Model\Hole;
use App\Model\Shot;
use App\Model\ShotsCollection;
use Exception;

class HuntTargetStrategy extends RandomStrategy
{
    public const ID = 3;

    /** @var ShotResult[] */
    private array $shotResults = [];

    private const HUNT_MODE = 0;
    private const TARGET_MODE = 1;

    protected static function create(Grid $grid, ShotsCollection $previousShots): self
    {
        $self = new self();
        $self->grid = $grid;
        $self->previousShots = $previousShots;
        foreach ($previousShots->items() as $shot) {
            $self->shotResults[] = $grid->shot($shot->hole());
        }
        return $self;
    }

    /**
     * @return int
     */
    public function calculateMode(): int
    {
        $shipResults = $this->calculateShipResults();
        $mode = self::HUNT_MODE;
        foreach ($shipResults as $shipId => $value) {
            if ($value === Grid::HIT) {
                $mode = self::TARGET_MODE;
            }
        }

        return $mode;
    }

    private function calculateShipResults(): array
    {
        $ships = [];
        /** @var PlacedShip $ship */
        foreach ($this->grid->ships() as $ship) {
            $ships[$ship->id()] = 0;
        }

        foreach ($this->shotResults as $shotResult) {
            if (($shotResult->shipId() > 0)) {
                if ($ships[$shotResult->shipId()] !== Grid::SUNK) {
                    $ships[$shotResult->shipId()] = $shotResult->result();

                }
            }
        }

        return $ships;
    }

    /**
     * @return ShotResult
     * @throws Exception
     */
    public function calculateShot(): Shot
    {
        $grid = $this->grid;
        $previousShots = $this->previousShots;
        /** @var Shot $shot */
        foreach ($previousShots as $shot) {
            $this->shotResults[] = $grid->shot($shot->hole());
        }

        if ($this->calculateMode() === self::HUNT_MODE) {
            return $this->calculateUniqueRandomShot();
        }

        return $this->calculateTargetShot();
    }

    protected function calculateTargetShot(): Shot
    {
        // Find a ship which is hit
        $shipResults = $this->calculateShipResults();
        $targetShipId = 0;
        foreach ($shipResults as $shipId => $shipResult) {
            if ($shipResult === Grid::HIT) {
                $targetShipId = $shipId;
            }
        }

        $estimatedHoles = [];
        foreach ($this->shotResults as $shotResult) {
            if ($shotResult->shipId() === $targetShipId) {
                $hole = $shotResult->hole();
                $letter = $hole->letter();
                $number = $hole->number();

                $prevLetter = Hole::convertNumberToLetter(Hole::convertLetterToNumber($letter) - 1);
                $nextLetter = Hole::convertNumberToLetter(Hole::convertLetterToNumber($letter) + 1);
                $prevNumber = $number - 1;
                $nextNumber = $number + 1;

                if (Hole::isValid($letter, $prevNumber)) {
                    $hole = Hole::createFromLetterAndNumber($letter, $prevNumber);
                    if ($this->isHoleAlreadyShot($hole) === false) {
                        $estimatedHoles[] = $hole;
                    }
                }

                if (Hole::isValid($letter, $nextNumber)) {
                    $hole = Hole::createFromLetterAndNumber($letter, $nextNumber);
                    if ($this->isHoleAlreadyShot($hole) === false) {
                        $estimatedHoles[] = $hole;
                    }
                }

                if (Hole::isValid($nextLetter, $number)) {
                    $hole = Hole::createFromLetterAndNumber($nextLetter, $number);
                    if ($this->isHoleAlreadyShot($hole) === false) {
                        $estimatedHoles[] = $hole;
                    }
                }

                if (Hole::isValid($prevLetter, $number)) {
                    $hole = Hole::createFromLetterAndNumber($prevLetter, $number);
                    if ($this->isHoleAlreadyShot($hole) === false) {
                        $estimatedHoles[] = $hole;
                    }
                }
            }
        }

        return new Shot($estimatedHoles[array_rand($estimatedHoles, 1)]);
    }
}
