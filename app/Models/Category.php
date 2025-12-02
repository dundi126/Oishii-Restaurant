<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'icon',
        'is_active',
        'sort_order'
    ];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function activeMenuItems()
    {
        return $this->hasMany(MenuItem::class)->where('is_active', true);
    }
}
