<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class PowerUtility extends Model
{
    protected $fillable = [
        'asset_tag',
        'type',
        'brand',
        'model',
        'serial_number',
        'capacity',
        'input_voltage',
        'output_voltage',
        'status',
        'location',
        'division',
        'department',
        'employee_id',
        'date_issued',
        'date_assigned',
        'date_returned',
        'spare_parts',
        'tracking_uuid',
        'updated_by',
        'department_id',
        'location_id',
        'division_id'
    ];

    protected $casts = [
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
        return $this->hasMany(PowerUtilityHistory::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->tracking_uuid)) {
                $model->tracking_uuid = (string)Str::uuid();
            }
            if (empty($model->asset_tag)) {
                $model->asset_tag = \App\Services\AssetTagService::generateNextTag(self::class, 'CAS-PU-');
            }
        });
    }

    public function getPublicUrlAttribute()
    {
        return route('public.asset.show', $this->tracking_uuid);
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
