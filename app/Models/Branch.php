<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'branch_name',
        'location'
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'branch_id');
    }

    public function pcUnits(): HasMany
    {
        return $this->hasMany(PcUnit::class, 'branch_id');
    }
}