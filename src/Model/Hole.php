<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\InvalidHoleArgumentException;

/**
 * Inspired from https://github.com/restgames/battleship-php
 * https://github.com/carlosbuenosvinos
 */
class Hole
{
    private const START_LETTER = 'A';
    private const END_LETTER = 'J';

    /** @var string */
    private $letter;

    /** @var int */
    private $number;

    public static function createFromLetterAndNumber(string $letter, int $number): Hole
    {
        if (strlen($letter) !== 1 || !in_array($letter, range(self::START_LETTER, self::END_LETTER), true)) {
            throw new InvalidHoleArgumentException(sprintf('The letter must be one capital letter between "%s" and "%s"', self::START_LETTER, self::END_LETTER));
        }

        if ($number > 10 || $number < 1) {
            throw new InvalidHoleArgumentException(sprintf('A number in between 1 and 10 is expected. %s given', $number));
        }

        return new self($letter, $number);
    }

    private function __construct(string $letter, int $number)
    {
        $this->letter = $letter;
        $this->number = $number;
    }

    public function letter(): string
    {
        return $this->letter;
    }

    public function number(): int
    {
        return $this->number;
    }

    public static function convertLetterToNumber(string $letter): int
    {
        return ord(strtoupper($letter)) - ord(self::START_LETTER) + 1;
    }

    public static function convertNumberToLetter(int $number): string
    {
        return chr(ord(self::START_LETTER) + $number - 1);
    }
}
