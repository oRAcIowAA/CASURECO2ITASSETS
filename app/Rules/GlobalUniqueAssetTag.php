<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GlobalUniqueAssetTag implements ValidationRule
{
    protected ?int $ignoreId;
    protected ?string $modelClass;

    public function __construct(?int $ignoreId = null, ?string $modelClass = null)
    {
        $this->ignoreId = $ignoreId;
        $this->modelClass = $modelClass;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value))
            return;

        // Check PC Units
        $query = \App\Models\PcUnit::where('asset_tag', $value);
        if ($this->ignoreId && $this->modelClass === \App\Models\PcUnit::class) {
            $query->where('id', '!=', $this->ignoreId);
        }
        if ($query->exists()) {
            $fail("The {$attribute} is already assigned to a PC Unit.");
            return;
        }

        // Check Network Devices
        $query = \App\Models\NetworkDevice::where('asset_tag', $value);
        if ($this->ignoreId && $this->modelClass === \App\Models\NetworkDevice::class) {
            $query->where('id', '!=', $this->ignoreId);
        }
        if ($query->exists()) {
            $fail("The {$attribute} is already assigned to a Network Device.");
            return;
        }

        // Check Printers
        $query = \App\Models\Printer::where('asset_tag', $value);
        if ($this->ignoreId && $this->modelClass === \App\Models\Printer::class) {
            $query->where('id', '!=', $this->ignoreId);
        }
        if ($query->exists()) {
            $fail("The {$attribute} is already assigned to a Printer.");
            return;
        }
    }
}
