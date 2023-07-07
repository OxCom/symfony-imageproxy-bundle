<?php

namespace SymfonyImageProxyBundle\Providers;

interface ImageProxyBuilderInterface
{
    public function url(string $img, array $options = []): string;
}
