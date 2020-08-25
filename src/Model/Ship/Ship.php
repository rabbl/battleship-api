<?php

declare(strict_types=1);

namespace App\Model\Ship;

abstract class Ship
{
    public function size(): int
    {
        return static::SIZE;
    }

    public function id(): int
    {
        return static::ID;
    }
}

