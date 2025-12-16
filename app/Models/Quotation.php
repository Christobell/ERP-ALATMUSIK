<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'rfq_id',
        'vendor_id',
        'vendor_name',
        'quotation_number',
        'submission_date',
        'valid_until',
        'total_amount',
        'currency',
        'payment_terms',
        'delivery_terms',
        'notes',
        'status',
        'document_path'
    ];

    protected $casts = [
        'submission_date' => 'date',
        'valid_until' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}