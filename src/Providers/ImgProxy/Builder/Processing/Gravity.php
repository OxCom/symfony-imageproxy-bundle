<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;

class Gravity implements ProcessOptionInterface
{
    private const GRAVITY_PREFIX = 'g:';
    private const GRAVITY_TPL = '%type:%x_offset:%y_offset';

    public function __construct(
        protected string $type = ImgProxy::GRAVITY_CENTER,
        protected int $x = 0,
        protected int $y = 0,
        protected bool $extends = false
    ) {
        $allowed = [
            ImgProxy::GRAVITY_CENTER,
            ImgProxy::GRAVITY_NORTH,
            ImgProxy::GRAVITY_SOUTH,
            ImgProxy::GRAVITY_EAST,
            ImgProxy::GRAVITY_WEST,
            ImgProxy::GRAVITY_NORTH_EAST,
            ImgProxy::GRAVITY_NORTH_WEST,
            ImgProxy::GRAVITY_SOUTH_EAST,
            ImgProxy::GRAVITY_SOUTH_WEST,
        ];

        if (!\in_array($type, $allowed, true)) {
            throw new \InvalidArgumentException(\sprintf("Gravity type '%s' is not allowed", $type));
        }
    }

    public function compile(): string
    {
        $tpl = self::GRAVITY_TPL;

        if (!$this->extends) {
            $tpl = self::GRAVITY_PREFIX . $tpl;
        }

        return \strtr($tpl, [
            '%type'     => $this->type,
            '%x_offset' => $this->x,
            '%y_offset' => $this->y,
        ]);
    }
}
