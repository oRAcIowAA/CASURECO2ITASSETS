<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileDeviceHistory extends Model
{
    protected $fillable = [
        'mobile_device_id',
        'employee_id',
        'previous_employee_id',
        'action',
        'notes',
        'created_by',
    ];

    public function mobileDevice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MobileDevice::class);
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
