<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorItem extends Model
{
    protected $fillable = [
        'vendor_id',
        'material_id',
        'vendor_price',
        'unit',
        'lead_time',
        'minimum_order',
        'notes'
    ];
    
    /**
     * Get the vendor that owns the item.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    
    /**
     * Get the material that owns the item.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}