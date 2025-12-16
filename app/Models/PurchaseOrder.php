<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'vendor_name',
        'order_date',
        'contact_person',
        'vendor_phone',
        'delivery_address',
        'total_amount',
        'status',
        'notes',
        'items'
    ];

    protected $casts = [
        'order_date' => 'date',
        'total_amount' => 'decimal:2',
        'items' => 'array'
    ];
}