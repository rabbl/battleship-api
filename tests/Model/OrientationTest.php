<?php

namespace App\Tests\Model;

use App\Exception\InvalidOrientationArgumentException;
use App\Model\Orientation;
use PHPUnit\Framework\TestCase;

class OrientationTest extends TestCase
{
    public function testOrientation(): void
    {
        $oh = Orientation::horizontal();
        self::assertTrue($oh->isHorizontal());
        self::assertFalse($oh->isVertical());

        $ov = Orientation::vertical();
        self::assertTrue($ov->isVertical());
        self::assertFalse($ov->isHorizontal());

        self::assertFalse($oh->equals($ov));
        self::assertTrue($oh->equals($oh));
        self::assertTrue($ov->equals($ov));
    }

    public function testCreateFromWrongIntThrowsException(): void
    {
        $this->expectException(InvalidOrientationArgumentException::class);
        Orientation::fromInt(2);
    }

    public function createFromIntDataProvider(): array
    {
        return [
            [0, true, false],
            [1, false, true],
        ];
    }

    /**
     * @dataProvider createFromIntDataProvider
     * @param $orientation
     * @param $isHorizontal
     * @param $isVertical
     */
    public function testCreateFromInt($orientation, $isHorizontal, $isVertical): void
    {
        $o = Orientation::fromInt($orientation);
        self::assertEquals($isHorizontal, $o->isHorizontal());
        self::assertEquals($isVertical, $o->isVertical());
        self::assertEquals($orientation, $o->toInt());
    }
}
