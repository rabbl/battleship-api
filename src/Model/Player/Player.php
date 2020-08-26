<?php

declare(strict_types=1);

namespace App\Model\Player;


use App\Model\Ship\PlacedShip;
use App\Model\Shot;

class Player
{
    /** @var string */
    private $name;

    /** @var PlacedShip[] */
    private $placedShips;

    /** @var Shot[] */
    private $placedShots;

    public static function fromArray(array $arr): Player
    {
        $name = $arr['name'];

        $placedShips = [];
        foreach ($arr['placedShips'] as $placedShip) {
            $placedShips[] = PlacedShip::fromArray($placedShip);
        }

        $placedShots = [];
        foreach ($arr['placedShots'] as $shot) {
            $placedShots[] = Shot::fromArray($shot);
        }

        return new self($name, $placedShips, $placedShots);
    }

    public function __construct(string $name, array $placedShips, array $placedShots = [])
    {
        $this->name = $name;
        $this->placedShips = $placedShips;
        $this->placedShots = $placedShots;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return PlacedShip[]
     */
    public function placedShips(): array
    {
        return $this->placedShips;
    }

    /**
     * @return Shot[]
     */
    public function placedShots(): array
    {
        return $this->placedShots;
    }

    public function addPlacedShot(Shot $shot): self
    {
        $this->placedShots[] = $shot;
        return $this;
    }

    public function toArray(): array
    {
        $placedShots = [];
        foreach ($this->placedShots() as $shot) {
            $placedShots[] = $shot->toArray();
        }

        $placedShips = [];
        foreach ($this->placedShips() as $placedShip) {
            $placedShips[] = $placedShip->toArray();
        }

        return [
            'name' => $this->name(),
            'placedShips' => $placedShips,
            'placedShots' => $placedShots
        ];
    }
}
