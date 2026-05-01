<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PcHistory extends Model
{
    protected $fillable = [
        'pc_unit_id',
        'employee_id',
        'previous_employee_id',
        'assigned_date',
        'returned_date',
        'action',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'returned_date' => 'date'
    ];

    public function pcUnit(): BelongsTo
    {
        return $this->belongsTo(PcUnit::class, 'pc_unit_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }

    public function previousEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'previous_employee_id')->withDefault();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
