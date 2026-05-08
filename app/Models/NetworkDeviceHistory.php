<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkDeviceHistory extends Model
{
    protected $fillable = [
        'network_device_id',
        'employee_id',
        'previous_employee_id',
        'assigned_date',
        'returned_date',
        'action',
        'notes',
        'created_by'
    ];

    public function networkDevice()
    {
        return $this->belongsTo(NetworkDevice::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }

    public function previousEmployee()
    {
        return $this->belongsTo(Employee::class , 'previous_employee_id')->withDefault();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class , 'created_by');
    }
}
