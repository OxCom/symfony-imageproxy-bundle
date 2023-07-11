<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy;

use SymfonyImageProxyBundle\Providers\ImageProxyBuilderInterface;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Url;

class Builder implements ImageProxyBuilderInterface
{
    public function __construct(private readonly Security $security, private readonly string $host)
    {
    }

    public function url(string $source, bool $secure = false): Url
    {
        return new Url($source, $this->host, $secure ? $this->security : null);
    }
}
