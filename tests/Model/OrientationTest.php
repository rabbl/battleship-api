<?php

namespace App\Tests\Model;

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

}
