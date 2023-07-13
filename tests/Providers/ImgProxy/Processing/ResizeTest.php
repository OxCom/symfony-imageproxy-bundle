<?php

namespace Tests\Providers\ImgProxy\Processing;

use PHPUnit\Framework\TestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Resize;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;

class ResizeTest extends TestCase
{
    /**
     * @dataProvider generateValidResizeType
     */
    public function testResizeType(string $type)
    {
        $resize = new Resize(0, 0, $type);
        self::assertNotNull($resize);
    }

    /**
     * @dataProvider generateValidResizeType
     */
    public function testInvalidResizeType(string $type)
    {
        $type .= 'Invalid';

        $this->expectException(\InvalidArgumentException::class);
        new Resize(0, 0, $type);
    }

    public function generateValidResizeType()
    {
        return [
            [ImgProxy::RESIZE_TYPE_FIT],
            [ImgProxy::RESIZE_TYPE_FILL],
            [ImgProxy::RESIZE_TYPE_FILL_DOWN],
            [ImgProxy::RESIZE_TYPE_FORCE],
            [ImgProxy::RESIZE_TYPE_AUTO],
        ];
    }
}
