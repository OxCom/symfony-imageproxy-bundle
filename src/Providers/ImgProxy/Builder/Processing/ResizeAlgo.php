<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\ProcessOptionInterface;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;

class ResizeAlgo implements ProcessOptionInterface
{
    private const RESIZE_TPL = 'ra:%algorithm';

    public function __construct(
        protected string $algo = ImgProxy::RESIZE_ALGO_LANCZOS3,
    ) {
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
    }

    public function compile(): string
    {
        return \strtr(self::RESIZE_TPL, [
            '%algorithm' => $this->algo,
        ]);
    }
}
