<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'manager_id',
        'description',
        'is_active'
    ];

    public function rfqs()
    {
        return $this->hasMany(Rfq::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}