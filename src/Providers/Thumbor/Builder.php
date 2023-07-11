<?php

namespace SymfonyImageProxyBundle\Providers\Thumbor;

use SymfonyImageProxyBundle\Providers\ImageProxyBuilderInterface;

class Builder implements ImageProxyBuilderInterface
{
    public function url(string $source, bool $secure = false): string
    {
        return $source;
    }
}
