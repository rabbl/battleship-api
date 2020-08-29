<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\InvalidHoleArgumentException;
use Exception;

/**
 * Inspired from https://github.com/restgames/battleship-php
 * https://github.com/carlosbuenosvinos
 */
class Hole
{
    /** @var string */
    private string $letter;

    /** @var int */
    private int $number;

    public static function createFromLetterAndNumber(string $letter, int $number): Hole
    {
        if (strlen($letter) !== 1 || !in_array($letter, range(Grid::START_LETTER, Grid::END_LETTER), true)) {
            throw new InvalidHoleArgumentException(sprintf('The letter must be one capital letter between "%s" and "%s". "%s" given.', Grid::START_LETTER, Grid::END_LETTER, $letter));
        }

        if ($number > Grid::END_NUMBER || $number < Grid::START_NUMBER) {
            throw new InvalidHoleArgumentException(sprintf('A number in between %s and %s is expected. %s given.', Grid::START_NUMBER, Grid::END_NUMBER, $number));
        }

        return new self($letter, $number);
    }

    public static function isValid(string $letter, int $number): bool
    {
        try {
            self::createFromLetterAndNumber($letter, $number);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public static function createFromArray(array $arr): Hole
    {
        return self::createFromLetterAndNumber($arr[0], $arr[1]);
    }

    /**
     * @return Hole
     * @throws Exception
     */
    public static function createRandom(): Hole
    {
        $number = random_int(Grid::START_NUMBER, Grid::END_NUMBER);
        $letter = self::convertNumberToLetter(
            random_int(
                self::convertLetterToNumber(Grid::START_LETTER),
                self::convertLetterToNumber(Grid::END_LETTER)
            )
        );

        return self::createFromLetterAndNumber($letter, $number);
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
        return ord(strtoupper($letter)) - ord(Grid::START_LETTER) + 1;
    }

    public static function convertNumberToLetter(int $number): string
    {
        return chr(ord(Grid::START_LETTER) + $number - 1);
    }

    public function toArray(): array
    {
        return [$this->letter(), $this->number()];
    }

    public function equals(Hole $other): bool
    {
        return $this->number() === $other->number() && $this->letter() === $other->letter();
    }
}
