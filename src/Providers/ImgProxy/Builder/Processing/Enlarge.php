<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;

class Enlarge implements ProcessOptionInterface
{
    private const ENLARGE_TPL = 'el:%enlarge';

    public function compile(): string
    {
        return \strtr(self::ENLARGE_TPL, ['%enlarge' => 1]);
    }
}
