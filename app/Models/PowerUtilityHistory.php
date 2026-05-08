<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PowerUtilityHistory extends Model
{
    protected $fillable = [
        'power_utility_id',
        'employee_id',
        'previous_employee_id',
        'action',
        'notes',
        'created_by',
    ];

    public function powerUtility(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PowerUtility::class);
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }

    public function previousEmployee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class, 'previous_employee_id')->withDefault();
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
