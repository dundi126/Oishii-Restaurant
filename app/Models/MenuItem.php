<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image_path',
        'is_vegetarian',
        'rating',
        'review_count',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_vegetarian' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Add this relationship for customizations
    public function customizations()
    {
        return $this->hasMany(MenuItemCustomization::class);
    }

    // Check if item has customizations
    public function getHasCustomizationsAttribute()
    {
        return $this->customizations()->exists();
    }

    // Get customizations by category type
    public function getCustomizationsByCategory()
    {
        $categoryName = $this->category->name;

        // You can add category-specific logic here if needed
        return $this->customizations()->orderBy('sort_order')->get();
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getDietTypeAttribute()
    {
        return $this->is_vegetarian ? 'Veg' : 'Non-Veg';
    }

    public function getDietColorAttribute()
    {
        return $this->is_vegetarian ? 'green' : 'red'; // Fixed colors - green for veg, red for non-veg
    }
}
