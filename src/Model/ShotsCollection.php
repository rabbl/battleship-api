<?php

declare(strict_types=1);

namespace App\Model;

class ShotsCollection
{
    private array $items;

    public static function create(): ShotsCollection
    {
        return new self();
    }

    public static function fromArray(array $arr): ShotsCollection
    {
        $self = new self();
        foreach ($arr as $item) {
            if (!$item instanceof Shot) {
                continue;
            }

            $self->add($item);
        }

        return $self;
    }

    private function __construct()
    {
        $this->items = [];
    }

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
