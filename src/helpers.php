<?php

if (!function_exists('format_megabytes')) {
    /**
     * Utility function to convert WHMCS package megabyte values.
     * Useful for pricing tables.
     *
     * @param $size
     *
     * @return string
     */
    function format_megabytes($size)
    {
        // If no size is set in WHMCS, this means unlimited.
        if (empty($size)) {
            return 'Unlimited';
        }

        // Convert to GB if over 1000 GB.
        if ($size >= 1000) {
            return $size * (1 / 1024) . ' GB';
        }

        // Finally, if none of the previous conditions are met we can assume the value is in MB.
        return $size . ' MB';
    }
}