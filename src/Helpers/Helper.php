<?php

namespace Vulpine\Helpers;

class Helper
{
    /**
     * Convert bytes into a more readable format.
     *
     * @param $size
     * @return string
     */
    public static function formatMegabytes($size)
    {
        if (empty($size)) {
            return 'Unlimited';
        } elseif ($size >= 1024) {
            return $size * (1 / 1024) . ' GB';
        }

        return $size . ' MB';
    }

    /**
     * Format a decimal to a readable monetary value in the correct currency.
     *
     * @param $value
     * @return string
     */
    public static function formatPrice($value)
    {
        setlocale(LC_MONETARY, config('vulpine.locale'));

        return utf8_encode(money_format('%n', $value));
    }
}