<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'po_number',
        'vendor_id',
        'order_date',
        'delivery_date',
        'status',
        'total_amount',
        'tax_amount',
        'grand_total',
        'notes',
        'created_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function getStatusColorAttribute()
{
    $colors = [
        'draft' => 'secondary',
        'pending' => 'warning',
        'approved' => 'success',
        'completed' => 'info',
        'rejected' => 'danger',
        'cancelled' => 'dark',
    ];
    return $colors[$this->status] ?? 'secondary';
}

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Generate PO Number
    public static function generatePONumber()
    {
        $prefix = 'PO-' . date('Ym');
        $lastPO = self::where('po_number', 'like', $prefix . '%')
            ->orderBy('po_number', 'desc')
            ->first();
        
        $number = $lastPO ? (int) substr($lastPO->po_number, -4) + 1 : 1;
        
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}