<?php

namespace SymfonyImageProxyBundle\Providers\Thumbor;

use SymfonyImageProxyBundle\Providers\ImageProxyBuilderInterface;

class Builder implements ImageProxyBuilderInterface
{
    public function __construct(private readonly Security $security, private readonly string $host)
    {
    }

    public function url(string $source, bool $secure = false): string
    {
        return $source;
    }
}
