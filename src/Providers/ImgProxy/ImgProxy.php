<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy;

class ImgProxy
{
    public const RESIZE_TYPE_FIT       = 'fit';
    public const RESIZE_TYPE_FILL      = 'fill';
    public const RESIZE_TYPE_FILL_DOWN = 'fill-down';
    public const RESIZE_TYPE_FORCE     = 'force';
    public const RESIZE_TYPE_AUTO      = 'auto';

    public const RESIZE_ALGO_LANCZOS3 = 'lanczos3';
    public const RESIZE_ALGO_LANCZOS2 = 'lanczos2';
    public const RESIZE_ALGO_NEAREST  = 'nearest';
    public const RESIZE_ALGO_LINEAR   = 'linear';
    public const RESIZE_ALGO_CUBIC    = 'cubic';

    public const GRAVITY_CENTER     = 'ce';
    public const GRAVITY_NORTH      = 'no';
    public const GRAVITY_SOUTH      = 'so';
    public const GRAVITY_EAST       = 'ea';
    public const GRAVITY_WEST       = 'we';
    public const GRAVITY_NORTH_EAST = 'noea';
    public const GRAVITY_NORTH_WEST = 'nowe';
    public const GRAVITY_SOUTH_EAST = 'soea';
    public const GRAVITY_SOUTH_WEST = 'sowe';
}
