<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $fillable = [
        'name',
        'code',
        'price',
        'stock',
        'image',
    ];

    public function bom()
    {
        return $this->hasMany(Bom::class);
    }

    public function mo()
    {
        return $this->hasMany(Mo::class);
    }
}
