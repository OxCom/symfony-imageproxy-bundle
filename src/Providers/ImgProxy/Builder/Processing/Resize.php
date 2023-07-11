<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;

class Resize implements ProcessOptionInterface
{
//    private const RESIZE_TPL = 'rs:%resizing_type:%width:%height:%enlarge:%extend';
    private const RESIZE_TPL = 'rs:%resizing_type:%width:%height';

    protected string $type;
    protected string $algo;

    public function __construct(
        protected int $width = 0,
        protected int $height = 0,
        string $type = ImgProxy::RESIZE_TYPE_FIT,
        string $algo = ImgProxy::RESIZE_ALGO_LANCZOS3
    ) {
        $this
            ->type($type)
            ->algo($algo);
    }

    public function type(string $type): self
    {
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

        $this->type = $type;

        return $this;
    }

    public function algo(string $algo): self
    {
        $allowed = [
            ImgProxy::RESIZE_ALGO_LANCZOS3,
            ImgProxy::RESIZE_ALGO_LANCZOS2,
            ImgProxy::RESIZE_ALGO_NEAREST,
            ImgProxy::RESIZE_ALGO_LINEAR,
            ImgProxy::RESIZE_ALGO_CUBIC,
        ];

        if (!\in_array($algo, $allowed, true)) {
            throw new \InvalidArgumentException(\sprintf("Resize algorithm '%s' is not allowed", $algo));
        }

        $this->algo = $algo;

        return $this;
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
