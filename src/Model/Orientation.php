<?php

declare(strict_types=1);

namespace App\Model;


use App\Exception\InvalidOrientationArgumentException;
use Exception;

class Orientation
{
    private const HORIZONTAL = 0;
    private const VERTICAL = 1;

    private $orientation;

    public static function horizontal(): Orientation
    {
        return new self(self::HORIZONTAL);
    }

    public static function vertical(): Orientation
    {
        return new self(self::VERTICAL);
    }

    /**
     * @return Orientation
     * @throws Exception
     */
    public static function createRandom(): Orientation
    {
        return self::fromInt(random_int(0, 1));
    }

    public static function fromInt(int $orientation): Orientation
    {
        if ($orientation !== self::HORIZONTAL && $orientation !== self::VERTICAL) {
            throw new InvalidOrientationArgumentException(sprintf('Orientation expected to be %s or %s, %s given', self::HORIZONTAL, self::VERTICAL, $orientation));
        }

        return new self($orientation);
    }

    private function __construct(int $orientation)
    {
        $this->orientation = $orientation;
    }

    public function isHorizontal(): bool
    {
        return $this->orientation === self::HORIZONTAL;
    }

    public function isVertical(): bool
    {
        return $this->orientation === self::VERTICAL;
    }

    public function toInt(): int
    {
        return $this->orientation;
    }

    public function equals(self $orientation): bool
    {
        return $this->toInt() === $orientation->toInt();
    }
}
