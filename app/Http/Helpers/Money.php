<?php

namespace App\Http\Helpers;


class Money
{
    /**
     * Try convert any value to decimal
     *
     * @param  int|float $value
     * @return float
     */
    public static function toDecimal(int|float $value): string
    {
        return number_format($value, 2, '.', '');
    }
}
