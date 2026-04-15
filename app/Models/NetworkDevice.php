<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NetworkDevice extends Model
{


    protected $fillable = [
        'asset_tag',
        'spare_parts',
        'device_type',
        'brand',
        'model',
        'department',
        'group',
        'division',
        'network_ports',
        'network_speed',
        'switch_type',
        'has_ip',
        'ip_address',
        'employee_id',
        'status',
        'spare_parts',
        'date_issued',
        'date_assigned',
        'date_returned',
        'tracking_uuid',
        'updated_by',
    ];

    protected $casts = [
        'has_ip' => 'boolean',
        'date_issued' => 'date',
        'date_assigned' => 'date',
        'date_returned' => 'date',
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function updatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function history()
    {
        return $this->hasMany(NetworkDeviceHistory::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->tracking_uuid)) {
                $model->tracking_uuid = (string)Str::uuid();
            }
            if (empty($model->asset_tag)) {
                $model->asset_tag = \App\Services\AssetTagService::generateNextTag(self::class, 'CAS-ND-');
            }
        });
    }

    public function getPublicUrlAttribute()
    {
        return route('public.asset.show', $this->tracking_uuid);
    }
}
