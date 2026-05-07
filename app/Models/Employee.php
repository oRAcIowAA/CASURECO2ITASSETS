<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'emp_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    protected $appends = ['id', 'full_name', 'employee_id'];

    protected $fillable = [
        'emp_id',
        'lname',
        'fname',
        'mname',
        'position',
        'department',
        'division',
        'location',
        'department_id',
        'division_id',
        'location_id'
    ];

    /**
     * ACCESSORS FOR SYSTEM COMPATIBILITY
     */

    public function getIdAttribute()
    {
        return $this->emp_id;
    }

    public function getEmployeeIdAttribute()
    {
        return $this->emp_id;
    }

    public function getFullNameAttribute()
    {
        if (empty($this->fname) && empty($this->lname)) {
            return 'N/A';
        }
        return strtoupper(trim("{$this->fname} " . ($this->mname ? "{$this->mname} " : "") . $this->lname));
    }

    public function getPositionAttribute()
    {
        return $this->attributes['position'] ?? 'N/A';
    }

    public function getDepartmentAttribute()
    {
        return $this->departmentRel->name ?? $this->attributes['department'] ?? 'N/A';
    }

    public function getDivisionAttribute()
    {
        return $this->divisionRel->name ?? $this->attributes['division'] ?? 'N/A';
    }

    public function getLocationAttribute()
    {
        return $this->locationRel->name ?? $this->attributes['location'] ?? 'N/A';
    }

    public function getGroupAttribute()
    {
        return $this->location;
    }

    /**
     * RELATIONSHIPS
     * Note: Foreign key on related tables is 'employee_id' (string)
     * Local key on this table is 'emp_id' (string)
     */

    public function pcUnits(): HasMany
    {
        return $this->hasMany(PcUnit::class , 'employee_id', 'emp_id');
    }

    public function pcHistory(): HasMany
    {
        return $this->hasMany(PcHistory::class , 'employee_id', 'emp_id');
    }

    public function printers(): HasMany
    {
        return $this->hasMany(Printer::class , 'employee_id', 'emp_id');
    }

    public function networkDevices(): HasMany
    {
        return $this->hasMany(NetworkDevice::class , 'employee_id', 'emp_id');
    }

    public function employeeHistory(): HasMany
    {
        return $this->hasMany(EmployeeHistory::class, 'employee_id', 'emp_id');
    }

    public function powerUtilities(): HasMany
    {
        return $this->hasMany(PowerUtility::class, 'employee_id', 'emp_id');
    }

    public function mobileDevices(): HasMany
    {
        return $this->hasMany(MobileDevice::class, 'employee_id', 'emp_id');
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
