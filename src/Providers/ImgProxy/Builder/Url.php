<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy\Builder;

use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Crop;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Dpr;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Enlarge;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Extend;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\ExtendAspectRatio;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Gravity;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Resize;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\ResizeAlgo;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder\Processing\Zoom;
use SymfonyImageProxyBundle\Providers\ImgProxy\ImgProxy;
use SymfonyImageProxyBundle\Providers\ImgProxy\Security;

class Url
{
    protected array $process = [];

    public function __construct(
        private readonly string $source,
        private string $host,
        private readonly ?Security $security = null
    ) {
        $parts      = \parse_url($this->host);
        $this->host = empty($parts['scheme']) ? ("https://" . $this->host) : $this->host;
    }

    public function resize(
        int $width = 0,
        int $height = 0,
        string $type = ImgProxy::RESIZE_TYPE_FIT,
    ): self {
        $this->process[Resize::class] = new Resize($width, $height, $type);

        return $this;
    }

    public function resizeAlgo(string $algo): self
    {
        $this->process[ResizeAlgo::class] = new ResizeAlgo($algo);

        return $this;
    }

    public function zoom(string $x = "1", ?string $y = null): self
    {
        $this->process[Zoom::class] = new Zoom($x, $y);

        return $this;
    }

    public function dpr(string $dpr = "1"): self
    {
        $this->process[Dpr::class] = new Dpr($dpr);

        return $this;
    }

    public function crop(
        int $width = 0,
        int $height = 0,
        ?string $type = null,
        ?int $x = 0,
        ?int $y = 0,
    ): self {
        $gravity = \is_null($type) ? null : new Gravity($type, $x, $y, true);
        $crop    = new Crop($width, $height, $gravity);

        $this->process[Crop::class] = $crop;

        return $this;
    }

    public function gravity(string $type, int $x = 0, int $y = 0): self
    {
        $this->process[Gravity::class] = new Gravity($type, $x, $y);

        return $this;
    }

    public function enlarge(): self
    {
        $this->process[Enlarge::class] = new Enlarge();

        return $this;
    }

    public function extend(
        ?string $type = null,
        ?int $x = 0,
        ?int $y = 0,
    ): self {
        $gravity = \is_null($type) ? null : new Gravity($type, $x, $y, true);
        $extend  = new Extend($gravity);

        $this->process[Extend::class] = $extend;

        return $this;
    }

    public function extendAspectRatio(
        ?string $type = null,
        ?int $x = 0,
        ?int $y = 0,
    ): self {
        $gravity = \is_null($type) ? null : new Gravity($type, $x, $y, true);
        $extend  = new ExtendAspectRatio($gravity);

        $this->process[ExtendAspectRatio::class] = $extend;

        return $this;
    }

    public function toPng(): string
    {
        return $this->build('png');
    }

    public function toJpeg(): string
    {
        return $this->build('jpg');
    }

    public function toWebP(): string
    {
        return $this->build('webp');
    }

    protected function build(string $format): string
    {
        $options = \array_map(static fn ($p) => $p->compile(), $this->process);
        $options = \implode('/', $options);

        $request = \implode('/', [
            $options,
            'plain',
            $this->source . '@' . $format,
        ]);

        $sign = $this->security ? $this->security->sign($request) : 'unsafe';

        return $this->host . '/' . $sign . '/' . $request;
    }
}
