<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PcUnit extends Model
{
    protected $fillable = [
        'device_type',
        'asset_tag',
        'model',
        'processor',
        'ram',
        'storage',
        'status',
        'ip_address',
        'mac_address',
        'network_segment',
        'date_received',
        'remarks',
        'branch_id',
        'department_id',
        'employee_id',
        'date_assigned',
        'date_returned',
        'assignment_notes'
    ];

    protected $casts = [
        'date_received' => 'date',
        'date_assigned' => 'date',
        'date_returned' => 'date'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(PcHistory::class , 'pc_unit_id');
    }
}