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
        'department_id'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class , 'department_id');
    }

    public function pcUnits(): HasMany
    {
        return $this->hasMany(PcUnit::class , 'employee_id');
    }

    public function pcHistory(): HasMany
    {
        return $this->hasMany(PcHistory::class , 'employee_id');
    }
}