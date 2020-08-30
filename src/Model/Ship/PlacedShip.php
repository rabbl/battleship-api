<?php

declare(strict_types=1);

namespace App\Model\Ship;

use App\Model\Hole;
use App\Model\Orientation;
use JsonSerializable;

class PlacedShip implements ShipInterface, JsonSerializable
{
    private Ship $ship;
    private Hole $hole;
    private Orientation $orientation;

    public static function fromArray($arr): PlacedShip
    {
        $ship = ShipFactory::build($arr['id']);
        $hole = Hole::createFromArray($arr['hole']);
        $orientation = Orientation::fromInt($arr['orientation']);

        return new self($ship, $hole, $orientation);
    }

    public function __construct(Ship $ship, Hole $hole, Orientation $orientation)
    {
        $this->ship = $ship;
        $this->hole = $hole;
        $this->orientation = $orientation;
    }

    public function ship(): Ship
    {
        return $this->ship;
    }

    public function hole(): Hole
    {
        return $this->hole;
    }

    public function orientation(): Orientation
    {
        return $this->orientation;
    }

    public function id(): int
    {
        return $this->ship()->id();
    }

    public function size(): int
    {
        return $this->ship()->size();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'hole' => $this->hole()->toArray(),
            'orientation' => $this->orientation()->toInt()
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
