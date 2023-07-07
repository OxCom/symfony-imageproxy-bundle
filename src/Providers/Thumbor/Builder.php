<?php

namespace SymfonyImageProxyBundle\Providers\Thumbor;

use SymfonyImageProxyBundle\Providers\ImageProxyBuilderInterface;

class Builder implements ImageProxyBuilderInterface
{
    public function url(string $img, array $options = []): string
    {
        return $img;
    }
}
