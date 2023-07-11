<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder;

interface ProcessOptionInterface
{
    public function compile(): string;
}
