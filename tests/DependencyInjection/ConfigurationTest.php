<?php

namespace Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder;

class ConfigurationTest extends KernelTestCase
{
    public function testParameters(): void
    {
        static::bootKernel();

        $container = static::$kernel->getContainer();
        $builder = $container->get(Builder::class);
    }
}
