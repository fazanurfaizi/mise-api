<?php

namespace App\Traits\TwoFactor;

use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

trait SerializesSharedSecret
{
    /**
     * Returns the shared secret as a URI.
     *
     * @return string
     */
    public function toUri(): string
    {
        $issuer = config('auth2fa.issuer', config('app.name'));

        $query = http_build_query([
            'issuer' => $issuer,
            'label' => $this->attributes['label'],
            'secret' => $this->shared_secret,
            'algorithm' => strtoupper($this->attributes['algorithm']),
            'digits' => $this->attributes['digits'],
        ], null, '&', PHP_QUERY_RFC3986);

        return 'otpauth://totp/' . rawurlencode($issuer) . '%3A' . $this->attributes['label'] . "?$query";
    }

    /**
     * Returns the shared secret as a QR Code in SVG format.
     *
     * @return string
     */
    public function toQr(): string
    {
        [$size, $margin] = array_values(config('auth2fa.qr_code'));

        return (
            new Writer((new ImageRenderer(new RendererStyle($size, $margin), new SvgImageBackEnd())))
        )->writeString($this->toUri());
    }

    /**
     * Returns the current object instance as a strig representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Returns the shared secret as a string
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->shared_secret;
    }

    /**
     * Returns the Shared Secret as a string of 4-character groups.
     *
     * @return string
     */
    public function toGroupedString() : string
    {
        return trim(chunk_split($this->toString(), 4, ' '));
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->toQr();
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toUri(), JSON_THROW_ON_ERROR | $options);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->toUri();
    }
}
