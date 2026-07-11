<?php

if (!function_exists('formatSizeFromBytes')) {
    /**
     * Format a file size in bytes to the most relevant unit (KB, MB, or GB).
     *
     * @param int|null $sizeInBytes
     * @return string
     */
    function formatSizeFromBytes($sizeInBytes) {
        if (!$sizeInBytes || $sizeInBytes <= 0) {
            return '—';
        }
        
        $kb = $sizeInBytes / 1024;
        
        if ($kb < 1024) {
            return number_format($kb, 2) . ' KB';
        }
        
        $mb = $kb / 1024;
        
        if ($mb < 1024) {
            return number_format($mb, 2) . ' MB';
        }
        
        $gb = $mb / 1024;
        return number_format($gb, 2) . ' GB';
    }
}
