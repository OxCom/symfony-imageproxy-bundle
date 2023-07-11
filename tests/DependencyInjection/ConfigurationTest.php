<?php

namespace Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder as ImgProxyBuilder;
use SymfonyImageProxyBundle\Providers\ImgProxy\Security as ImgProxySecurity;
use SymfonyImageProxyBundle\Providers\Thumbor\Builder as ThumborBuilder;
use SymfonyImageProxyBundle\Providers\Thumbor\Security as ThumborSecurity;
use Tests\ReflectPropsTrait;

class ConfigurationTest extends KernelTestCase
{
    use ReflectPropsTrait;

    public function setUp(): void
    {
        parent::setUp();
        static::bootKernel();
    }

    public function testImgProxyRegistered(): void
    {
        $container = static::$kernel->getContainer();
        $builder = $container->get(ImgProxyBuilder::class);

        $this->assertInstanceOf(ImgProxyBuilder::class, $builder);
    }

    public function testImgProxyConfigLoaded(): void
    {
        $container = static::$kernel->getContainer();
        $builder   = $container->get(ImgProxyBuilder::class);

        $props = $this->getObjectProperties($builder, [
            'security',
            'host'
        ]);

        self::assertEquals('conv.imgproxy.awesome.com', $props['host']);
        self::assertInstanceOf(ImgProxySecurity::class, $props['security']);
    }

    public function testThumborRegistered(): void
    {
        $container = static::$kernel->getContainer();
        $builder   = $container->get(ThumborBuilder::class);

        $this->assertInstanceOf(ThumborBuilder::class, $builder);
    }

    public function testThumborConfigLoaded(): void
    {
        $container = static::$kernel->getContainer();
        $builder   = $container->get(ThumborBuilder::class);

        $props = $this->getObjectProperties($builder, [
            'security',
            'host'
        ]);

        self::assertEquals('conv.imgproxy.awesome.com', $props['host']);
        self::assertInstanceOf(ThumborSecurity::class, $props['security']);
    }

    public function tearDown(): void
    {
        static::ensureKernelShutdown();
        parent::tearDown();
    }
}
