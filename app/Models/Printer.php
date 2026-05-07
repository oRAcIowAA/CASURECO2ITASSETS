<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Printer extends Model
{


    protected $fillable = [
        'asset_tag',
        'type',
        'brand',
        'model',
        'serial_number',
        'department',
        'location',
        'division',
        'has_network_port',
        'ip_type',
        'ip_address',
        'mac_address',
        'network_segment',
        'employee_id',
        'status',
        'spare_parts',
        'date_issued',
        'date_assigned',
        'date_returned',
        'tracking_uuid',
        'updated_by',
        'department_id',
        'location_id',
        'division_id'
    ];

    protected $casts = [
        'has_network_port' => 'boolean',
        'date_issued' => 'date',
        'date_assigned' => 'date',
        'date_returned' => 'date',
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id')->withDefault();
    }

    public function updatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function history()
    {
        return $this->hasMany(PrinterHistory::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->tracking_uuid)) {
                $model->tracking_uuid = (string)Str::uuid();
            }
            if (empty($model->asset_tag)) {
                $model->asset_tag = \App\Services\AssetTagService::generateNextTag(self::class, 'CAS-PR-');
            }
        });
    }

    public function getPublicUrlAttribute()
    {
        return route('public.asset.show', $this->tracking_uuid);
    }

    /**
     * MAC Address formatting logic (Mirrored from PcUnit)
     */
    public function getMacAddressAttribute($value)
    {
        return $value ? strtoupper($value) : $value;
    }

    public function setMacAddressAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['mac_address'] = null;
            return;
        }

        $clean = preg_replace('/[^a-fA-F0-9]/', '', $value);
        if (strlen($clean) === 12) {
            $formatted = implode(':', str_split(strtoupper($clean), 2));
            $this->attributes['mac_address'] = $formatted;
        } else {
            $this->attributes['mac_address'] = strtoupper($value);
        }
    }

    public function getLocationAttribute($value)
    {
        if (strtolower($this->status) === 'assigned' && $this->employee && $this->employee->exists) {
            return $this->employee->location;
        }
        return $this->locationRel->name ?? $value ?? 'N/A';
    }

    public function getDepartmentAttribute($value)
    {
        if (strtolower($this->status) === 'assigned' && $this->employee && $this->employee->exists) {
            return $this->employee->department;
        }
        return $this->departmentRel->name ?? $value ?? 'N/A';
    }

    public function getDivisionAttribute($value)
    {
        if (strtolower($this->status) === 'assigned' && $this->employee && $this->employee->exists) {
            return $this->employee->division;
        }
        return $this->divisionRel->name ?? $value ?? 'N/A';
    }

    public function getGroupAttribute()
    {
        return $this->location;
    }

    public function departmentRel()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function locationRel()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function divisionRel()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
}
