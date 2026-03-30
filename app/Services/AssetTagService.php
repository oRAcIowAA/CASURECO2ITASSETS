<?php

namespace App\Services;

use Illuminate\Support\Str;

class AssetTagService
{
    /**
     * Generate the next asset tag for a given model and prefix.
     *
     * @param string $modelClass
     * @param string $prefix
     * @return string
     */
    public static function generateNextTag(string $modelClass, string $prefix): string
    {
        // Find the latest asset tag with the given prefix
        $latest = $modelClass::where('asset_tag', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(asset_tag, "-", -1) AS UNSIGNED) DESC')
            ->first();

        if (!$latest) {
            return $prefix . '001';
        }

        // Extract the last number and increment it
        $lastTag = $latest->asset_tag;
        $lastNumberStr = Str::afterLast($lastTag, '-');
        
        if (!is_numeric($lastNumberStr)) {
            // Fallback if the format is somehow broken
            return $prefix . '001';
        }

        $lastNumber = (int) $lastNumberStr;
        $nextNumber = $lastNumber + 1;
        
        // Pad with zeros (at least 3 digits)
        return $prefix . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
