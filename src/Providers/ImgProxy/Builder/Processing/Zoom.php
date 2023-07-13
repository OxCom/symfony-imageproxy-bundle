<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;

class Zoom implements ProcessOptionInterface
{
    private const ZOOM_TPL = 'z:%zoom_x_y';
    private const ZOOM_XY_TPL = 'z:%zoom_x:%zoom_y';

    public function __construct(protected string $zoomX = "1", protected ?string $zoomY = null)
    {
        $this->zoomY = \is_null($this->zoomY) ? $this->zoomX : $this->zoomY;

        if (!\is_numeric($this->zoomX)) {
            throw new \InvalidArgumentException('Zoom multiplier X must be numeric.');
        }

        if ((float)$this->zoomX <= 0) {
            throw new \InvalidArgumentException('Zoom multiplier X must be greater then 0.');
        }

        if (!\is_numeric($this->zoomY)) {
            throw new \InvalidArgumentException('Zoom multiplier Y must be numeric.');
        }

        if ((float)$this->zoomY <= 0) {
            throw new \InvalidArgumentException('Zoom multiplier Y must be greater then 0.');
        }
    }

    public function compile(): string
    {
        $equal = $this->zoomX === $this->zoomY;
        $tpl = $equal ? self::ZOOM_TPL : self::ZOOM_XY_TPL;

        return \strtr($tpl, $equal
            ? ['%zoom_x_y' => $this->zoomX]
            : ['%zoom_x' => $this->zoomX, '%zoom_y' => $this->zoomY]
        );
    }
}
