<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchase_order_items';
    
    protected $fillable = [
        'purchase_order_id',
        'material_id',
        'quantity',
        'unit_price',
        'subtotal_price',
        'description'
    ];
    
    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal_price' => 'decimal:2',
    ];
    
    // RELATIONSHIPS
    
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    
    // METHODS
    
    public function getFormattedUnitPriceAttribute()
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }
    
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal_price, 0, ',', '.');
    }
}