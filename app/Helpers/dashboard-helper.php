<?php

if (!function_exists('formatSizeFromMb')) {
    /**
     * Format a file size in megabytes to the most relevant unit (KB, MB, or GB).
     *
     * @param float|null $sizeInMb
     * @return string
     */
    function formatSizeFromMb($sizeInMb) {
        if (!$sizeInMb || $sizeInMb <= 0) {
            return '—';
        }
        if ($sizeInMb < 1) {
            $kb = $sizeInMb * 1024;
            return round($kb, 1) . ' KB';
        }
        if ($sizeInMb >= 1024) {
            $gb = $sizeInMb / 1024;
            return number_format($gb, 2) . ' GB';
        }
        return number_format($sizeInMb, 2) . ' MB';
    }
}
