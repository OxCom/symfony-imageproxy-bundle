<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;

class ExtendAspectRatio implements ProcessOptionInterface
{
    private const EXTEND_TPL = 'exar:%extend';
    private const EXTEND_GRAVITY_TPL = 'exar:%extend:%gravity';

    public function __construct(private readonly ?Gravity $gravity = null)
    {
    }

    public function compile(): string
    {
        $tpl = $this->gravity ? self::EXTEND_GRAVITY_TPL : self::EXTEND_TPL;

        return \strtr($tpl, [
            '%extend'  => 1,
            '%gravity' => $this->gravity ? $this->gravity->compile() : '',
        ]);
    }
}
