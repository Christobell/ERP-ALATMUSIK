<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';
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
    
}
