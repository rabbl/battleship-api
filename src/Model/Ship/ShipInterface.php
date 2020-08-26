<?php

declare(strict_types=1);

namespace App\Model\Ship;

interface ShipInterface
{
    public function id(): int;

    public function size(): int;
}
