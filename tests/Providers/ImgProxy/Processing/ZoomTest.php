<?php

namespace Tests\Providers\ImgProxy\Processing;

use PHPUnit\Framework\TestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Zoom;

class ZoomTest extends TestCase
{
    /**
     * @dataProvider generateNumericX
     */
    public function testNumericX(string $x)
    {
        $z = new Zoom($x);

        self::assertEquals("z:{$x}", $z->compile());
    }

    public function generateNumericX()
    {
        return [
            ['1'],
            ['1.1'],
            ['0.33'],
            ['10.33'],
            ['0.27'],
        ];
    }

    /**
     * @dataProvider generateXLessZero
     */
    public function testXLessZero(string $x)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Zoom multiplier X must be greater then 0.');
        new Zoom($x);
    }

    public function generateXLessZero()
    {
        return [
            ['0'],
            ['-0.33'],
            ['-100'],
        ];
    }

    /**
     * @dataProvider generateNumericY
     */
    public function testNumericY(string $y)
    {
        $z = new Zoom(0.7, $y);

        self::assertEquals("z:0.7:{$y}", $z->compile());
    }

    public function generateNumericY()
    {
        return [
            ['1'],
            ['1.1'],
            ['0.33'],
            ['10.33'],
            ['0.27'],
        ];
    }

    /**
     * @dataProvider generateYLessZero
     */
    public function testYLessZero(string $y)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Zoom multiplier Y must be greater then 0.');
        new Zoom(0.7, $y);
    }

    public function generateYLessZero()
    {
        return [
            ['0'],
            ['-0.33'],
            ['-100'],
        ];
    }

    public function testEqualXY()
    {
        $z = new Zoom(0.33, 0.7);
        self::assertEquals("z:0.33:0.7", $z->compile());

        $z = new Zoom(0.33, 0.33);
        self::assertEquals("z:0.33", $z->compile());
    }
}
