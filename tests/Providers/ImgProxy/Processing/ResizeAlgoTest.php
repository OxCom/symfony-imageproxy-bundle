<?php

namespace Tests\Providers\ImgProxy\Processing;

use PHPUnit\Framework\TestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\ResizeAlgo;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;

class ResizeAlgoTest extends TestCase
{
    /**
     * @dataProvider generateValidAlgo
     */
    public function testAllowedResizeAlgo(string $algo)
    {
        $rs = new ResizeAlgo($algo);
        self::assertEquals("ra:{$algo}", $rs->compile());
    }

    /**
     * @dataProvider generateValidAlgo
     */
    public function testInalidResizeAlgo(string $algo)
    {
        $algo .= 'Invalid';
        $this->expectException(\InvalidArgumentException::class);
        new ResizeAlgo($algo);
    }

    public function generateValidAlgo()
    {
        return [
            [ImgProxy::RESIZE_ALGO_LANCZOS3],
            [ImgProxy::RESIZE_ALGO_LANCZOS2],
            [ImgProxy::RESIZE_ALGO_NEAREST],
            [ImgProxy::RESIZE_ALGO_LINEAR],
            [ImgProxy::RESIZE_ALGO_CUBIC],
        ];
    }
}
