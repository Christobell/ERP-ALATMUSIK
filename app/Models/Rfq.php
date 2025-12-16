<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfq extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'rfq_number',
        'title',
        'description',
        'request_date',
        'deadline_date',
        'requested_by',
        'department_id',
        'estimated_budget',
        'status',
        'items',
        'notes'
    ];

    protected $casts = [
        'request_date' => 'date',
        'deadline_date' => 'date',
        'estimated_budget' => 'decimal:2',
        'items' => 'array'
    ];

    // Hapus relationships yang butuh tabel lain
    // public function requester()
    // {
    //     return $this->belongsTo(User::class, 'requested_by');
    // }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'pending' => 'warning',
            'quotation_received' => 'info',
            'evaluating' => 'primary',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'dark',
            default => 'light'
        };
    }
}