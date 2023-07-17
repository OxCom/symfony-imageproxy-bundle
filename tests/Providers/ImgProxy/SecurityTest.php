<?php

namespace Tests\Providers\ImgProxy;

use PHPUnit\Framework\TestCase;
use SymfonyImageProxyBundle\Providers\ImgProxy\Security;
use Tests\ReflectPropsTrait;

class SecurityTest extends TestCase
{
    use ReflectPropsTrait;

    public function testKeyMustBeHEXEncoded()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The sign key must be hex-encoded string');
        
        new Security('xyz', 'xyz');
    }

    public function testSaltMustBeHEXEncoded()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The sign key must be hex-encoded string');

        new Security('617765736F6D65', 'xyz');
    }

    public function testKeyAndSaltShouldBeCaseInsensetive()
    {
        $s1 = new Security('617765736F6D65', '6F78636F6D');
        $s2 = new Security('617765736F6d65', '6f78636f6d');

        $props1 = $this->getObjectProperties($s1, ['key', 'salt']);
        $props2 = $this->getObjectProperties($s2, ['key', 'salt']);

        self::assertEquals($props1, $props2);
    }

    public function testSignPayload()
    {
        $payload = "/resize:fit:1920:0:0:false:ce:0:0/plain/https://www.awesome.com/files/image.jpg@webp";
        $s = new Security('617765736F6D65', '6F78636F6D');

        $signature = $s->sign($payload);

        self::assertEquals('T5v8rp-cI42Iq_ycqYt3y9iYcxHKcGiKkID4KIJblmU', $signature);
        self::assertFalse(\mb_strpos($signature, '='));
        self::assertFalse(\mb_strpos($signature, '+'));
        self::assertFalse(\mb_strpos($signature, '/'));
    }

    public function testCoppedSignaturePayload()
    {
        $payload = "/resize:fit:1920:0:0:false:ce:0:0/plain/https://www.awesome.com/files/image.jpg@webp";
        $s       = new Security('617765736F6D65', '6F78636F6D', 8);

        $signature = $s->sign($payload);

        self::assertEquals('T5v8rp-cI40', $signature);
        self::assertFalse(\mb_strpos($signature, '='));
        self::assertFalse(\mb_strpos($signature, '+'));
        self::assertFalse(\mb_strpos($signature, '/'));
    }
}
