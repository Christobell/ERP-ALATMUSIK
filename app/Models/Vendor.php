<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'payment_terms',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    /**
     * Get the items for the vendor.
     */
    public function items()
    {
        return $this->hasMany(VendorItem::class);
    }
    
    /**
     * Get the purchase orders for the vendor.
     * KOMENTARI DULU KARENA MODEL PurchaseOrder BELUM ADA
     */
    /*
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    */
    
    /**
     * Scope a query to only include active vendors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}