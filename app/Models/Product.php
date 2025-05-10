<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'price'
    ];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function getCurrentStockAttribute()
    {
        $in = $this->inventory()->where('type', 'in')->sum('quantity');
        $out = $this->inventory()->where('type', 'out')->sum('quantity');
        return $in - $out;
    }
} 