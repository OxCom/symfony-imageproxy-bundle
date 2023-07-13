<?php

namespace Tests\Providers\ImgProxy;

use PHPUnit\Framework\TestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;
use SymfonyImageProxyBundle\Providers\ImgProxy\Security;
use Tests\ReflectPropsTrait;

class BuilderTest extends TestCase
{
    use ReflectPropsTrait;

    public function testReturnUrlObject()
    {
        $img = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder = new Builder($security, 'conv.awesome.com');

        $url = $builder->url($img);

        $data = $this->getObjectProperties($url, [
            'source',
            'host',
            'security'
        ]);

        self::assertEquals($img, $data['source']);
        self::assertEquals('https://conv.awesome.com', $data['host']);
        self::assertNull($data['security']);
    }

    public function testReturnSecureUrlObject()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder->url($img, true);

        $data = $this->getObjectProperties($url, [
            'source',
            'host',
            'security'
        ]);

        self::assertEquals($img, $data['source']);
        self::assertEquals('https://conv.awesome.com', $data['host']);
        self::assertInstanceOf(Security::class, $data['security']);
    }

    /**
     * @dataProvider generateCrop
     */
    public function testCrop(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, $host);

        $url = $builder
            ->url($img, $secure)
            ->crop(33, 42)
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/c:33:42/plain/{$img}@webp",
            $url
        );
    }

    public function generateCrop()
    {
        return [
            [false, 'unsafe'],
            [true, '4ZuzK5flQncpAJTm06kNLZgjfBF9Uvg-9IbzUzgQiFU'],
        ];
    }

    /**
     * @dataProvider generateCropGravity
     */
    public function testCropGravity(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, $host);

        $url = $builder
            ->url($img, $secure)
            ->crop(33, 42, ImgProxy::GRAVITY_NORTH_EAST, 77, 101)
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/c:33:42:noea:77:101/plain/{$img}@webp",
            $url
        );
    }

    public function generateCropGravity()
    {
        return [
            [false, 'unsafe'],
            [true, 'WhFQ9qDT8x7AJ-kkZ8r1B2FGZR4cBxC5nAl-Tn2fRlk'],
        ];
    }

    /**
     * @dataProvider generateDpr
     */
    public function testDpr(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->dpr(0.33)
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/dpr:0.33/plain/{$img}@webp",
            $url
        );
    }

    public function generateDpr()
    {
        return [
            [false, 'unsafe'],
            [true, 'rvd5ARX2BnIEUX-YdG6pjW9sJJxtObJeq-7qqasvVB0']
        ];
    }

    /**
     * @dataProvider generateEnlarge
     */
    public function testEnlarge(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->enlarge()
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/el:1/plain/{$img}@webp",
            $url
        );
    }

    public function generateEnlarge()
    {
        return [
            [false, 'unsafe'],
            [true, 'KusQZACqKGtRROqNRw9XI-FymSN5_jF1Uf5vw9o6fAY'],
        ];
    }

    /**
     * @dataProvider generateExtend
     */
    public function testExtend(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extend()
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/ex:1/plain/{$img}@webp",
            $url
        );
    }

    public function generateExtend()
    {
        return [
            [false, 'unsafe'],
            [true, 'fIMZOe6sFyHfg7nOPC1RE2n5FPnGUN0cF_XAc73iciE'],
        ];
    }

    /**
     * @dataProvider generateExtendGravity
     */
    public function testExtendGravity(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extend(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/ex:1:we:42:32/plain/{$img}@webp",
            $url
        );
    }

    public function generateExtendGravity()
    {
        return [
            [false, 'unsafe'],
            [true, 'cjrByJoooiA-fuulQXeyYQ__PCswuFgUc9IFRCTCQas'],
        ];
    }

    /**
     * @dataProvider generateExtendAspectRatio
     */
    public function testExtendAspectRatio(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extendAspectRatio()
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/exar:1/plain/{$img}@webp",
            $url
        );
    }

    public function generateExtendAspectRatio()
    {
        return [
            [false, 'unsafe'],
            [true, '6QtWBzsJzMgYlZp3skIw1yqlUiOzBkLCqi3Yka9ZfFg'],
        ];
    }

    /**
     * @dataProvider generateExtendAspectRatioGravity
     */
    public function testExtendAspectRatioGravity(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extendAspectRatio(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/exar:1:we:42:32/plain/{$img}@webp",
            $url
        );
    }

    public function generateExtendAspectRatioGravity()
    {
        return [
            [false, 'unsafe'],
            [true, 'IYf2dFQH2b0h5-EZWdfnmQ2p5-DIWvdIQ32mJK7CtOw'],
        ];
    }

    /**
     * @dataProvider generateGravity
     */
    public function testGravity(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->gravity(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/g:we:42:32/plain/{$img}@webp",
            $url
        );
    }

    public function generateGravity()
    {
        return [
            [false, 'unsafe'],
            [true, 'pvUSHMEoxBpdO04lSb86hVute9llZ5Je8KMrPZkqQ1o'],
        ];
    }

    /**
     * @dataProvider generateResize
     */
    public function testResize(bool $secure, string $sign, string $type)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder->url($img, $secure);

        self::assertEquals(
            "https://{$host}/{$sign}/rs:{$type}:33:42/plain/{$img}@webp",
            $url->resize(33, 42, $type)->toWebP()
        );
    }

    public function generateResize()
    {
        return [
            [false, 'unsafe', ImgProxy::RESIZE_TYPE_FIT],
            [false, 'unsafe', ImgProxy::RESIZE_TYPE_FILL],
            [true, 't_itJJxpz7xn29uXsw94hoPAgtjoH1ndjDcVpzqYvjs', ImgProxy::RESIZE_TYPE_FIT],
            [true, 'w5OhxTUBsyWSAyYBKLM5XmD0gKQC0HBIXte2WKdzGas', ImgProxy::RESIZE_TYPE_FILL],
        ];
    }

    /**
     * @dataProvider generateResizeAlgo
     */
    public function testResizeAlgo(bool $secure, string $sign)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->resize(33, 42)
            ->resizeAlgo(ImgProxy::RESIZE_ALGO_NEAREST)
            ->toWebP();

        self::assertEquals(
            "https://{$host}/{$sign}/rs:fit:33:42/ra:nearest/plain/{$img}@webp",
            $url
        );
    }

    public function generateResizeAlgo()
    {
        return [
            [false, 'unsafe'],
            [true, 'gZySmmOp7MjA2pUDCakkhrvmO7BnWfldVDK_53o0u3k'],
        ];
    }

    /**
     * @dataProvider generateZoom
     */
    public function testZoom(bool $secure, string $sign, string $zoom, float $x, ?float $y = null)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder->url($img, $secure);

        self::assertEquals(
            "https://{$host}/{$sign}/{$zoom}/plain/{$img}@webp",
            $url->zoom($x, $y)->toWebP()
        );
    }

    public function generateZoom()
    {
        return [
            [false, 'unsafe', 'z:0.5', 0.5, null],
            [false, 'unsafe', 'z:0.5:0.7', 0.5, 0.7],
            [false, 'unsafe', 'z:0.33', 0.33, 0.33],
            [true, '89dxf-LVEaWoZ94YqYhxrFfEnwT_9Ycltf6ogL0ZKTo', 'z:0.5', 0.5, null],
            [true, 'TzzGWtsfGHicel4-Um_wEdKQj8J-y02UWFlKJAfT3W0', 'z:0.5:0.7', 0.5, 0.7],
            [true, 'GIVMg-A9hW6KDaHL6tBiNO3GfTCLBCIL_XQwBwzbAXI', 'z:0.33', 0.33, 0.33],
        ];
    }

    /**
     * @dataProvider generateImageType
     */
    public function testImageType(bool $secure, string $sign, string $toType, string $ext)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->resize(33)
            ->{$toType}();

        self::assertEquals(
            "https://{$host}/{$sign}/rs:fit:33:0/plain/{$img}@{$ext}",
            $url
        );
    }

    public function generateImageType()
    {
        return [
            [false, 'unsafe', 'toPng', 'png'],
            [false, 'unsafe', 'toJpeg', 'jpg'],
            [false, 'unsafe', 'toWebP', 'webp'],
            [true, 'f4hbRvgnEA3oKFlRPNnPhbHnOLKNWBr-b6tem3y4-F8', 'toPng', 'png'],
            [true, 'WKw4gcv31OlYKJR23MMCzgvNdvg36YuIz_ynJ0dHWo8', 'toJpeg', 'jpg'],
            [true, '2nfqtrOsPhesVatbaWIxzPZSNEFPaPILAH9MM2uwZh0', 'toWebP', 'webp'],
        ];
    }
}
