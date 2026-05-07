<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['id', 'name', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
