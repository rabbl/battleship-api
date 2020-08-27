<?php

declare(strict_types=1);

namespace App\Model\Ship;

abstract class Ship implements ShipInterface
{
    public const SIZE = null;
    public const ID = null;

    public function size(): int
    {
        return static::SIZE;
    }

    public function id(): int
    {
        return static::ID;
    }
}

