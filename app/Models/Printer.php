<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Printer extends Model
{


    protected $fillable = [
        'asset_tag',
        'brand',
        'model',
        'department',
        'group',
        'division',
        'has_network_port',
        'ip_address',
        'employee_id',
        'status',
        'spare_parts',
        'date_assigned',
        'date_returned',
        'tracking_uuid',
    ];

    protected $casts = [
        'has_network_port' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
        });
    }

    public function getPublicUrlAttribute()
    {
        return route('public.asset.show', $this->tracking_uuid);
    }
}
