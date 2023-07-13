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
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder->url($img);

        $data = $this->getObjectProperties($url, [
            'source',
            'host',
            'security',
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
            'security',
        ]);

        self::assertEquals($img, $data['source']);
        self::assertEquals('https://conv.awesome.com', $data['host']);
        self::assertInstanceOf(Security::class, $data['security']);
    }

    /**
     * @dataProvider generateCrop
     */
    public function testCrop(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, $host);

        $url = $builder
            ->url($img, $secure)
            ->crop(33, 42)
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/c:33:42/{$encImg}",
            $url
        );
    }

    public function generateCrop()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                '4ZuzK5flQncpAJTm06kNLZgjfBF9Uvg-9IbzUzgQiFU',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'ZDEAv4k8CrDcXwcOeqNkb5zK3pdyF8k21KIT_zlQ8zM',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateCropGravity
     */
    public function testCropGravity(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, $host);

        $url = $builder
            ->url($img, $secure)
            ->crop(33, 42, ImgProxy::GRAVITY_NORTH_EAST, 77, 101)
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/c:33:42:noea:77:101/{$encImg}",
            $url
        );
    }

    public function generateCropGravity()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'WhFQ9qDT8x7AJ-kkZ8r1B2FGZR4cBxC5nAl-Tn2fRlk',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'VZDWTO4t5emfTedoHXDlRjThxFmUOIoGVvysZLDvF0E',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateDpr
     */
    public function testDpr(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->dpr(0.33)
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/dpr:0.33/{$encImg}",
            $url
        );
    }

    public function generateDpr()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'rvd5ARX2BnIEUX-YdG6pjW9sJJxtObJeq-7qqasvVB0',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                '1w7nIfoTrCIIQPBl71RkQ368qXXImiVPVEe1gVCpG98',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateEnlarge
     */
    public function testEnlarge(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->enlarge()
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/el:1/{$encImg}",
            $url
        );
    }

    public function generateEnlarge()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'KusQZACqKGtRROqNRw9XI-FymSN5_jF1Uf5vw9o6fAY',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'WivjLnXvjCvdIxgwa5HSTEgbOQimJYB6K0WgPNrUjhw',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateExtend
     */
    public function testExtend(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extend()
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/ex:1/{$encImg}",
            $url
        );
    }

    public function generateExtend()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'fIMZOe6sFyHfg7nOPC1RE2n5FPnGUN0cF_XAc73iciE',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'ws3sBthJ0pC3mgQLzZAbtyJJA1MkF4x38e416US-B6c',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateExtendGravity
     */
    public function testExtendGravity(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extend(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/ex:1:we:42:32/{$encImg}",
            $url
        );
    }

    public function generateExtendGravity()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'cjrByJoooiA-fuulQXeyYQ__PCswuFgUc9IFRCTCQas',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'x--ZvCZEDxLDm--6BMFQ_SOmWbRdtl55mfR4z7Mpbfc',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateExtendAspectRatio
     */
    public function testExtendAspectRatio(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extendAspectRatio()
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/exar:1/{$encImg}",
            $url
        );
    }

    public function generateExtendAspectRatio()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                '6QtWBzsJzMgYlZp3skIw1yqlUiOzBkLCqi3Yka9ZfFg',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'mLtLRKOlWlUOetwm2dDDDCsnS4JJRBCtxaDdMk8IMoo',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateExtendAspectRatioGravity
     */
    public function testExtendAspectRatioGravity(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->extendAspectRatio(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/exar:1:we:42:32/{$encImg}",
            $url
        );
    }

    public function generateExtendAspectRatioGravity()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'IYf2dFQH2b0h5-EZWdfnmQ2p5-DIWvdIQ32mJK7CtOw',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'UeX7qMDFxjxrja21_UlTeDNd3-aoFtXgk6MFngLrKCw',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
        ];
    }

    /**
     * @dataProvider generateGravity
     */
    public function testGravity(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->gravity(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/g:we:42:32/{$encImg}",
            $url
        );
    }

    public function generateGravity()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'pvUSHMEoxBpdO04lSb86hVute9llZ5Je8KMrPZkqQ1o',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'jqagygpgF6xWEEHfMAYvTFedqjmEbyNZf1_-G-6tr2g',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
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
            "https://{$host}/{$sign}/rs:{$type}:33:42/aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp",
            $url->resize(33, 42, $type)->toWebP()
        );
    }

    public function generateResize()
    {
        return [
            [false, 'unsafe', ImgProxy::RESIZE_TYPE_FIT],
            [false, 'unsafe', ImgProxy::RESIZE_TYPE_FILL],
            [true, 'bAdpOGNzrgE8q2SHx6r9reD-dknZHEoSGRp9X1aUdXM', ImgProxy::RESIZE_TYPE_FIT],
            [true, 'UPaXrIMHuY-Or_qibSLlfg0wV2QnIcShX0i0hB3ST9c', ImgProxy::RESIZE_TYPE_FILL],
        ];
    }

    /**
     * @dataProvider generateResizeAlgo
     */
    public function testResizeAlgo(bool $secure, string $sign, string $type, string $encImg)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->resize(33, 42)
            ->resizeAlgo(ImgProxy::RESIZE_ALGO_NEAREST)
            ->toWebP($type);

        self::assertEquals(
            "https://{$host}/{$sign}/rs:fit:33:42/ra:nearest/{$encImg}",
            $url
        );
    }

    public function generateResizeAlgo()
    {
        return [
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_PLAIN, 'plain/https://awesome.com/awesome/image.jpg@webp'],
            [false, 'unsafe', ImgProxy::SOURCE_TYPE_BASE64, 'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp'],
            [
                true,
                'gZySmmOp7MjA2pUDCakkhrvmO7BnWfldVDK_53o0u3k',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'IaW0BovXjOMZAzC9edAIOy2Gf2XCuNbkt3bTzjnH2Y4',
                ImgProxy::SOURCE_TYPE_BASE64,
                'aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp',
            ],
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
            "https://{$host}/{$sign}/{$zoom}/aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.webp",
            $url->zoom($x, $y)->toWebP()
        );
    }

    public function generateZoom()
    {
        return [
            [false, 'unsafe', 'z:0.5', 0.5, null],
            [false, 'unsafe', 'z:0.5:0.7', 0.5, 0.7],
            [false, 'unsafe', 'z:0.33', 0.33, 0.33],
            [true, 'sNFs_ZM5cBSSRXb6hZsbyzPYUhx6P4xJseppd4MIeYc', 'z:0.5', 0.5, null],
            [true, 'RNi4R9HNy-uZ7IfsAGh4zPxBWM4_9uETOGaLjjZWQqY', 'z:0.5:0.7', 0.5, 0.7],
            [true, 'xc_MFz4udqxjn-tkJ7yBwhWX1J9MLft9Puiw8jfUcK8', 'z:0.33', 0.33, 0.33],
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
            "https://{$host}/{$sign}/rs:fit:33:0/aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.{$ext}",
            $url
        );
    }

    public function generateImageType()
    {
        return [
            [false, 'unsafe', 'toPng', 'png'],
            [false, 'unsafe', 'toJpeg', 'jpg'],
            [false, 'unsafe', 'toWebP', 'webp'],
            [true, '6ZwNkrNUfCyPIB20pjsJSEvVxpAqo2y_K07j9fwL4vA', 'toPng', 'png'],
            [true, 'ujhiDwdFuobz7gVIbodu6qYnlvtv5dqZde0K4u4632E', 'toJpeg', 'jpg'],
            [true, '8bG3BQeX_0CfrQyUufqJ1xe_ERMsuaMtnQXS3qWONJ8', 'toWebP', 'webp'],
        ];
    }

    /**
     * @dataProvider generateComplexBuildProcess
     */
    public function testComplexBuildProcess(bool $secure, string $sign, string $toType, string $ext)
    {
        $host = 'conv.awesome.com';
        $img  = 'https://awesome.com/awesome/image.jpg';

        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img, $secure)
            ->gravity(ImgProxy::GRAVITY_NORTH_EAST, 3, 7)
            ->enlarge()
            ->extend(ImgProxy::GRAVITY_CENTER, 33, 42)
            ->crop(1024, 786)
            ->resize(768, 0, ImgProxy::RESIZE_TYPE_FORCE)
            ->zoom(0.33)
            ->{$toType}();

        $options = 'g:noea:3:7/el:1/ex:1:ce:33:42/c:1024:786/rs:force:768:0/z:0.33';

        self::assertEquals(
            "https://{$host}/{$sign}/{$options}/aHR0cHM6Ly9hd2Vzb21lLmNvbS9hd2Vzb21lL2ltYW/dlLmpwZw.{$ext}",
            $url
        );
    }

    public function generateComplexBuildProcess()
    {
        return [
            [false, 'unsafe', 'toPng', 'png'],
            [false, 'unsafe', 'toJpeg', 'jpg'],
            [false, 'unsafe', 'toWebP', 'webp'],
            [true, 'ednUiSeCvzf3vDi8Eu5ag8btNC4dh2HlgxPc3SPu8GM', 'toPng', 'png'],
            [true, 'IRdxxLt4cpMIyXejdaFixyP5duHGtRsxZp_gRygnUr8', 'toJpeg', 'jpg'],
            [true, 'wqauSTfykiNRShXvrXrEwg7QRKekEWXJvVzvzewoEW4', 'toWebP', 'webp'],
        ];
    }
}
