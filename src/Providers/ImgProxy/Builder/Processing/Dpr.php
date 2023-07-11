<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;

class Dpr implements ProcessOptionInterface
{
    private const DPR_TPL = 'dpr:%dpr';

    public function __construct(protected string $dpr = "1")
    {
    }

    public function compile(): string
    {
        return \strtr(self::DPR_TPL, [
            '%dpr' => $this->dpr
        ]);
    }
}
