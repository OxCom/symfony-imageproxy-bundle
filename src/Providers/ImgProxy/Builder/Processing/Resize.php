<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;

class Resize implements ProcessOptionInterface
{
    private const RESIZE_TPL = 'rs:%resizing_type:%width:%height';
    public function __construct(
        protected int $width = 0,
        protected int $height = 0,
        protected string $type = ImgProxy::RESIZE_TYPE_FIT
    ) {
        $allowed = [
            ImgProxy::RESIZE_TYPE_FIT,
            ImgProxy::RESIZE_TYPE_FILL,
            ImgProxy::RESIZE_TYPE_FILL_DOWN,
            ImgProxy::RESIZE_TYPE_FORCE,
            ImgProxy::RESIZE_TYPE_AUTO,
        ];

        if (!\in_array($type, $allowed, true)) {
            throw new \InvalidArgumentException(\sprintf("Resize type '%s' is not allowed", $type));
        }
    }

    public function compile(): string
    {
        return \strtr(self::RESIZE_TPL, [
            '%resizing_type' => $this->type,
            '%width'         => $this->width,
            '%height'        => $this->height,
        ]);
    }
}
