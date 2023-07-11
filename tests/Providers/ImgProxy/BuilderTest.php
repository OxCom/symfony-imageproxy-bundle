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

    public function testCrop()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->crop(33, 42)
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/c:33:42/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testCropGravity()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->crop(33, 42, ImgProxy::GRAVITY_NORTH_EAST, 77, 101)
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/c:33:42:noea:77:101/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testDpr()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->dpr(0.33)
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/dpr:0.33/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testEnlarge()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->enlarge()
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/el:1/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testExtend()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->extend()
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/ex:1/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testExtendGravity()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->extend(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/ex:1:we:42:32/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testExtendAspectRatio()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->extendAspectRatio()
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/exar:1/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testExtendAspectRatioGravity()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->extendAspectRatio(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/exar:1:we:42:32/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testGravity()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder
            ->url($img)
            ->gravity(ImgProxy::GRAVITY_WEST, 42, 32)
            ->toWebP();

        self::assertEquals(
            'https://conv.awesome.com/unsafe/g:we:42:32/plain/https://awesome.com/awesome/image.jpg@webp',
            $url
        );
    }

    public function testResize()
    {
        $img      = 'https://awesome.com/awesome/image.jpg';
        $security = new Security('617765736F6D65', '6F78636F6D');
        $builder  = new Builder($security, 'conv.awesome.com');

        $url = $builder->url($img);

        self::assertEquals(
            'https://conv.awesome.com/unsafe/rs:fit:33:42/plain/https://awesome.com/awesome/image.jpg@webp',
            $url->resize(33, 42)->toWebP()
        );

        self::assertEquals(
            'https://conv.awesome.com/unsafe/rs:fit:33:42/plain/https://awesome.com/awesome/image.jpg@webp',
            $url->toWebP()
        );
    }
}
