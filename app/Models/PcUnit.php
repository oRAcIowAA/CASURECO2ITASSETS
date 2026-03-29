<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PcUnit extends Model
{
    protected $fillable = [
        'device_type',
        'asset_tag',
        'spare_parts',
        'model',
        'processor',
        'ram',
        'storage',
        'status',
        'ip_address',
        'mac_address',
        'network_segment',
        'department',
        'group',
        'division',
        'date_received',
        'remarks',
        'employee_id',
        'date_assigned',
        'date_returned',
        'assignment_notes',
        'tracking_uuid'
    ];

    protected $casts = [
        'date_received' => 'date',
        'date_assigned' => 'date',
        'date_returned' => 'date'
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(PcHistory::class , 'pc_unit_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->tracking_uuid)) {
                $model->tracking_uuid = (string)Str::uuid();
            }
        });
    }

    public function getPublicUrlAttribute()
    {
        return route('public.asset.show', $this->tracking_uuid);
    }

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
}