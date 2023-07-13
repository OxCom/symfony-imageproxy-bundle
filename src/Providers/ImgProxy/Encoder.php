<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy;

class Encoder
{
    public static function encode(string $payload): string
    {
        return \rtrim(\strtr(\base64_encode($payload), '+/', '-_'), '=');
    }
}
