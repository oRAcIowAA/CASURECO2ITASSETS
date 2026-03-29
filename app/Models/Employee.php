<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'full_name',
        'position',
        'department',
        'group',
        'division'
    ];

    public function pcUnits(): HasMany
    {
        return $this->hasMany(PcUnit::class , 'employee_id');
    }

    public function pcHistory(): HasMany
    {
        return $this->hasMany(PcHistory::class , 'employee_id');
    }

    public function printers(): HasMany
    {
        return $this->hasMany(Printer::class , 'employee_id');
    }

    public function networkDevices(): HasMany
    {
        return $this->hasMany(NetworkDevice::class , 'employee_id');
    }
}