<?php

namespace App\Traits\TwoFactor;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait HandlesRecoveryCodes
{
    /**
     * Returns if there are Recovery codes available
     *
     * @return bool
     */
    public function containsUnusedRecoveryCodes(): bool
    {
        return (bool) $this->recovery_codes?->contains('used_at', '==', null);
    }

    /**
     * Returns the key of the not-used Recovery code.
     *
     * @param string $code
     *
     * @return int|bool|null
     */
    protected function getUnusedRecoveryCodeIndex(string $code): int|bool|null
    {
        return $this->recovery_codes?->search([
            'code' => $code,
            'used_at' => null
        ]);
    }

    /**
     * Sets a Recovery code as used.
     *
     * @param string $code
     *
     * @return bool
     */
    public function setRecoveryCodeAsUsed(string $code): bool
    {
        if(!is_int($index = $this->getUnusedRecoveryCodeIndex($code))) {
            return false;
        }

        $this->recovery_codes = $this->recovery_codes->put($index, [
            'code' => $code,
            'used_at' => now()
        ]);

        return true;
    }

    /**
     * Generates a new batch of Recovery codes.
     *
     * @param int $amount
     * @param int $length
     *
     * @return \Illuminate\Support\Collection
     */
    public static function generateRecoveryCodes(int $amount, int $length): Collection
    {
        return Collection::times($amount, static function () use ($length): array {
            return [
                'code' => strtoupper(Str::random($length)),
                'used_at' => null
            ];
        });
    }
}
