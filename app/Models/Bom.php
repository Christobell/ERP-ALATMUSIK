<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    protected $table = 'bom';
    protected $fillable = [
        'product_id',
        'total_price',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function mo()
    {
        return $this->hasMany(Mo::class);
    }

    public function bomItem()
    {
        return $this->hasMany(BomItem::class);
    }
}
