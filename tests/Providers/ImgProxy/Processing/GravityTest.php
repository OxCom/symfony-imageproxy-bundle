<?php

namespace Tests\Providers\ImgProxy\Processing;

use PHPUnit\Framework\TestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Gravity;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;

class GravityTest extends TestCase
{
    /**
     * @dataProvider generateValidGravityType
     */
    public function testAllowedTypes(string $type)
    {
        $g = new Gravity($type);
        $this->assertNotNull($g);
    }

    /**
     * @dataProvider generateValidGravityType
     */
    public function testIvalidTypes(string $type)
    {
        $type .= 'Invalid';

        $this->expectException(\InvalidArgumentException::class);
        new Gravity($type);
    }

    public function generateValidGravityType()
    {
        return [
            [ImgProxy::GRAVITY_CENTER],
            [ImgProxy::GRAVITY_NORTH],
            [ImgProxy::GRAVITY_SOUTH],
            [ImgProxy::GRAVITY_EAST],
            [ImgProxy::GRAVITY_WEST],
            [ImgProxy::GRAVITY_NORTH_EAST],
            [ImgProxy::GRAVITY_NORTH_WEST],
            [ImgProxy::GRAVITY_SOUTH_EAST],
            [ImgProxy::GRAVITY_SOUTH_WEST],
        ];
    }
}
