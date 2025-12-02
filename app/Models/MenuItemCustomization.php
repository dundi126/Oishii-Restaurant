<?php
// app/Models/MenuItemCustomization.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemCustomization extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'name',
        'type',
        'required',
        'options',
        'sort_order'
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean'
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Helper to get options with prices
    public function getOptionsWithPrices()
    {
        return collect($this->options)->map(function ($option) {
            return [
                'value' => $option['value'],
                'price' => $option['price'] ?? 0,
                'formatted_price' => $option['price'] > 0 ? '+$' . number_format($option['price'], 2) : 'Free'
            ];
        });
    }
}
