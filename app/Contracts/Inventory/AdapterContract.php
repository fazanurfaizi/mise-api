<?php

namespace App\Contracts\Inventory;

interface AdapterContract
{
    public function transform(): array;

    public function setResource($resource): void;

    public static function collection($collection): array;
}
