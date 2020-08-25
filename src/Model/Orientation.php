<?php

declare(strict_types=1);

namespace App\Model;


class Orientation
{

    /** @var bool */
    private $isHorizontal;

    public static function fromHorizontal(): Orientation
    {
        return new self(true);
    }

    public static function fromVertical(): Orientation
    {
        return new self(false);
    }

    private function __construct(bool $isHorizontal)
    {
        $this->isHorizontal = $isHorizontal;
    }

    public function isHorizontal(): bool
    {
        return $this->isHorizontal;
    }

    public function isVertical(): bool
    {
        return !$this->isHorizontal;
    }

    public function equals(self $orientation)
    {
        return $this->isHorizontal() === $orientation->isHorizontal();
    }
}
