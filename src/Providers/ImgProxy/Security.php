<?php

namespace SymfonyImageProxyBundle\Providers\ImgProxy;
class Security
{
    private string $key;
    private string $salt;

    public function __construct(string $key, string $salt, private readonly int $size = 32)
    {
        if (\mb_strlen($key) > 0) {
            try {
                $this->key = \pack('H', \mb_strtoupper($key));
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException('The sign key must be hex-encoded string', $e->getCode(), $e);
            }
        }

        if (\mb_strlen($salt) > 0) {
            try {
                $this->salt = \pack('H', \mb_strtoupper($salt));
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException('The sign key must be hex-encoded string', $e->getCode(), $e);
            }
        }
    }

    /**
     * A signature is a URL-safe Base64-encoded HMAC digest of the rest of the path, including the leading /
     *
     * @see https://docs.imgproxy.net/signing_the_url?id=calculating-url-signature
     */
    public function sign(string $payload): string
    {
        $signature = \hash_hmac('sha256', $this->salt . $payload, $this->key, true);

        return $this->encode($this->crop($signature));
    }

    /**
     * Return URL friendly base64 encoded string
     */
    private function encode(string $payload): string
    {
        return Encoder::encode($payload);
    }

    /**
     * Number of bytes to use for signature before encoding to Base64
     */
    private function crop(string $signature)
    {
        return \pack('A' . $this->size, $signature);
    }
}
