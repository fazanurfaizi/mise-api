<?php

namespace App\Utils;

use App\Traits\Models\HasPrice;

class Price
{
    use HasPrice;

    public int $cent;

    public int $amount;

    public string $formatted;

    public string $currency;

    public function __construct(int $cent) {
        $this->cent = $cent;
        $this->amount = $cent / 100;
        $this->currency = mise_currency();
        $this->formatted = $this->formattedPrice($this->amount);
    }

    /**
     * Create price attribute.
     *
     * @param int $cent
     *
     * @return self
     */
    public static function from(int $cent): self
    {
        return new self($cent);
    }
}
