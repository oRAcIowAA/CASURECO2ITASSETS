<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GlobalUniqueMac implements ValidationRule
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
        $query = \App\Models\PcUnit::where('mac_address', $value);
        if ($this->ignoreId && $this->modelClass === \App\Models\PcUnit::class) {
            $query->where('id', '!=', $this->ignoreId);
        }
        if ($query->exists()) {
            $fail("The {$attribute} is already in use by a PC Unit.");
            return;
        }

        // Check Network Devices (if the table has a mac_address column)
        if (\Illuminate\Support\Facades\Schema::hasColumn('network_devices', 'mac_address')) {
            $query = \App\Models\NetworkDevice::where('mac_address', $value);
            if ($this->ignoreId && $this->modelClass === \App\Models\NetworkDevice::class) {
                $query->where('id', '!=', $this->ignoreId);
            }
            if ($query->exists()) {
                $fail("The {$attribute} is already in use by a Network Device.");
                return;
            }
        }

        // Check Printers (if the table has a mac_address column)
        if (\Illuminate\Support\Facades\Schema::hasColumn('printers', 'mac_address')) {
            $query = \App\Models\Printer::where('mac_address', $value);
            if ($this->ignoreId && $this->modelClass === \App\Models\Printer::class) {
                $query->where('id', '!=', $this->ignoreId);
            }
            if ($query->exists()) {
                $fail("The {$attribute} is already in use by a Printer.");
                return;
            }
        }
    }
}
