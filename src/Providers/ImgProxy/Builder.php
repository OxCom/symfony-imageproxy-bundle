<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy;

use SymfonyImageProxyBundle\Providers\ImageProxyBuilderInterface;

class Builder implements ImageProxyBuilderInterface
{
    public function __construct(private readonly Security $security, private readonly string $host)
    {
    }

    public function url(string $img, array $options = []): string
    {
        return $img;
    }
}
