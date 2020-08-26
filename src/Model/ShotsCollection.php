<?php

declare(strict_types=1);

namespace App\Model;

class ShotsCollection
{
    private $items = [];

    public function add(Shot $shot): self
    {
        $this->items[] = $shot;
        return $this;
    }

    public function items(): array
    {
        return $this->items;
    }
}
