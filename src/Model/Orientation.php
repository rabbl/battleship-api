<?php

declare(strict_types=1);

namespace App\Model;


class Orientation
{

    /** @var bool */
    private $isHorizontal;

    public static function horizontal(): Orientation
    {
        return new self(true);
    }

    public static function vertical(): Orientation
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

    public function equals(self $orientation): bool
    {
        return $this->isHorizontal() === $orientation->isHorizontal();
    }
}
