<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Exception\InvalidHoleArgumentException;
use App\Model\Hole;
use Exception;
use PHPUnit\Framework\TestCase;

class HoleTest extends TestCase
{

    public function invalidHoleParametersDataProvider(): array
    {
        return [
            ['a', 1],
            ['K', 1],
            ['A', 0],
            ['A', 11],
            ['More then one letter', 1],
        ];
    }

    /**
     * @dataProvider invalidHoleParametersDataProvider
     * @param $letter
     * @param $number
     */
    public function testItThrowsExceptionWhenLetterOutOfBounds($letter, $number): void
    {
        $this->expectException(InvalidHoleArgumentException::class);
        Hole::createFromLetterAndNumber($letter, $number);
    }

    public function allValidHoleParametersDataProvider(): iterable
    {
        foreach (range(1, 10) as $number) {
            foreach (range('A', 'J') as $letter) {
                yield [$letter, $number];
            }
        }
    }

    /**
     * @dataProvider allValidHoleParametersDataProvider
     */
    public function testLetterAndNumberWillBeReturnedCorrectly($letter, $number): void
    {
        $hole = Hole::createFromLetterAndNumber($letter, $number);
        self::assertEquals($letter, $hole->letter());
        self::assertEquals($number, $hole->number());
    }

    public function letterNumberDataProvider(): array
    {
        return [
            ['A', 1],
            ['B', 2],
            ['C', 3],
            ['D', 4],
            ['E', 5],
            ['F', 6],
            ['G', 7],
            ['H', 8],
        ];
    }

    /**
     * @dataProvider letterNumberDataProvider
     * @param string $expectedLetter
     * @param int $number
     */
    public function testLetterToNumberReturnsCorrectResult(string $expectedLetter, int $number): void
    {
        self::assertEquals($expectedLetter, Hole::convertNumberToLetter($number));
    }

    /**
     * @dataProvider letterNumberDataProvider
     * @param string $letter
     * @param int $expectedNumber
     */
    public function testNumberToLetterReturnsCorrectResult(string $letter, int $expectedNumber): void
    {
        self::assertEquals($expectedNumber, Hole::convertLetterToNumber($letter));
    }

    /**
     * @throws Exception
     * @noinspection UnnecessaryAssertionInspection
     */
    public function testItCreatesRandomHoles(): void
    {
        self::assertInstanceOf(Hole::class, Hole::createRandom());
    }
}
