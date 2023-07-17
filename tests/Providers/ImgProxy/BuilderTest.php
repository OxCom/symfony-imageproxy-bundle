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
                'jtQ3I5whBLtbs85xD26yECxFIm29nzNrn9ROk5vBfuQ',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'ah29tGoAcM97vqM0dGjcRIwQ3Mdgz7NPCrOT-1o9pMg',
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
                'E1cBecdqB6zkXp041gbvcNJldc7dOxeqvTCDo_33HNM',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'rq4N9iEMrGUAhIpMypbK-7dLr3WX-gpa0n8q4TR8iI8',
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
                'QqY3iTkr42sC4qFsU-G9HVACtRro-8Rf72EcoUGPAcI',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'OTfaX-TMSPFNeKgPPN3RZ4Q3WICT67bhuIQxIs9X1CI',
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
                'rAZRT3ErCkjspdBOuWa0pxQ3GK1T0w_yn55V51gyorg',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'I8ds9XUcaCC5dJjzGyvpyqJgGY20wi-Q8wZSp51deGA',
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
                'hpijWw5oUgIJjl-ShSXNH9zW3qOH4wf_5RW3bwxAyDI',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'ywgqQUBVIDOCqTBMPmx1HXktlxqv2xZRd8FQ8G5jsFI',
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
                '7ROyDkctUWjsWkdJAnUvN1TS8uYpD5EGv-RSN8utBaU',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'FqEm3K5tQMdbDYrglmOmJGuWH895bT9PyviwjG781Qo',
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
                'HsNGkuQkgZ9S-KtPrPLv71t2wHGZjj8NtVgRsyg8Q0w',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'IGcZECl0lUi-MRYYbd49RSiqloSlJUEAC5bqAuIONXc',
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
                'x6gIo8cmk4PnLLEMQn1Zb6a-d8S9Qm-YRtWf_csy6cA',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                '1JSwN3pL0S-4XAePoOgdYc-Y0J3iOUnE5y3a_Itmok8',
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
                'wAwihJuBjiSnEOOPJ976VEaNRRMnp6T03xCWU-sZno8',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'iV92ktHke8mSHYo0bL2v_dyKdYN7vAZvnKB34utKStE',
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
            [true, 'rHQIIV3WcYtFMhdv0bDv1dmAjlGURAth3a_VkyXy1dA', ImgProxy::RESIZE_TYPE_FIT],
            [true, 'tcLCrY0E0v9xbZupH55LoI9GQC1slCPZcrcjb110jps', ImgProxy::RESIZE_TYPE_FILL],
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
                'bNGfNfBusP3uznF8u6nvaVGA8V3muAUWqDT1OeS_2NM',
                ImgProxy::SOURCE_TYPE_PLAIN,
                'plain/https://awesome.com/awesome/image.jpg@webp',
            ],
            [
                true,
                'qzF_0UlpGZ3mTtOjms8ouyrl-on-w35KmKup8dKqjak',
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
            [true, 'kao3RgWcIBtFxsYahGXd2-Mm-nZCytph7vpmIDNY-b4', 'z:0.5', 0.5, null],
            [true, 'zPMU7alxq-7MG1cddx8mmbKSJxXJY008xSdaR_Fm2eI', 'z:0.5:0.7', 0.5, 0.7],
            [true, '2_9bzlV9YdbCbrsACGNcZaIFKq5q15OYl2YkYZqgodU', 'z:0.33', 0.33, 0.33],
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
            [true, '1MXYS2HZX_kx4Mc48G2A3FHYBbFQcdZOG93cGF36lBI', 'toPng', 'png'],
            [true, 'B4C-i6nQK7BcM1pkDur2ASESNFXFqJj9yFoFFAgpf80', 'toJpeg', 'jpg'],
            [true, '8k-2Oaymn9_jlxxMWXra6E0UeSwTSb1O83Kx0iYwBSI', 'toWebP', 'webp'],
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
            [true, 'C1eUqt2bBdFHw9mNIkUfXtljxzu1AknswKYvApjOyKM', 'toPng', 'png'],
            [true, 'hqdxqvP2M0v6z8exnu87mJFI4BMKpi6uTZ3RoeSN7cE', 'toJpeg', 'jpg'],
            [true, 'o5KSJpzJ_KNTZrAF-HbnLle8Zcg7VLatlo8et5adfTQ', 'toWebP', 'webp'],
        ];
    }
}
