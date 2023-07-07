<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy;
class Security
{
    public function __construct(private readonly string $key, private readonly string $salt)
    {
    }

    public function sign(string $img): string
    {
        return $this->key . $img . $this->salt;
    }
}
