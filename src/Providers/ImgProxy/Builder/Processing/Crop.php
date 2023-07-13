<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;

class Crop implements ProcessOptionInterface
{
    private const CROP_TPL = 'c:%width:%height';
    private const CROP_GRAVITY_TPL = 'c:%width:%height:%gravity';

    public function __construct(
        protected int $width = 0,
        protected int $height = 0,
        protected ?Gravity $gravity = null
    ) {
    }

    public function compile(): string
    {
        $tpl     = $this->gravity ? self::CROP_GRAVITY_TPL : self::CROP_TPL;
        $gravity = $this->gravity ? $this->gravity->compile() : '';

        return \strtr($tpl, [
            '%width'   => $this->width,
            '%height'  => $this->height,
            '%gravity' => $gravity,
        ]);
    }
}
