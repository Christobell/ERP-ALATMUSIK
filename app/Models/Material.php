<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'material';

    protected $fillable = [
        'name',
        'code',
        'price',
        'stock',
        'image', 
      
    ];

    // Opsional: accessor untuk URL gambar lengkap
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-material.png'); // gambar default
    }
}