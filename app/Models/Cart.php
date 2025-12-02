<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_item_id',
        'quantity',
        'customizations',
        'customization_price'
    ];

    protected $casts = [
        'customizations' => 'array',
        'customization_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Calculate total price for this cart item
    public function getTotalPriceAttribute()
    {
        $basePrice = $this->menuItem->price;
        $customizationPrice = $this->customization_price ?? 0;
        return ($basePrice + $customizationPrice) * $this->quantity;
    }

    public function getFormattedTotalPriceAttribute()
    {
        return '$' . number_format($this->total_price, 2);
    }

    // Get customizations as readable string
    public function getCustomizationsTextAttribute()
    {
        if (empty($this->customizations)) {
            return 'No customizations';
        }

        $text = [];
        foreach ($this->customizations as $key => $value) {
            if (is_array($value)) {
                $text[] = ucfirst($key) . ': ' . implode(', ', $value);
            } else {
                $text[] = ucfirst($key) . ': ' . $value;
            }
        }

        return implode(' | ', $text);
    }

    // Get cart for user with menu items
    // In your Cart model - update this method
    public static function getCartForUser($userId)
    {
        return static::with('menuItem.category')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($cart) {
                // Force calculation of total_price by accessing it
                $cart->total_price_calculated = $cart->total_price;
                return $cart;
            });
    }
    // Get cart count for user
    public static function getCartCount($userId)
    {
        return static::where('user_id', $userId)->sum('quantity');
    }

    // Get cart total price for user
    public static function getCartTotal($userId)
    {
        return static::where('user_id', $userId)
            ->with('menuItem')
            ->get()
            ->sum(function ($cart) {
                $basePrice = $cart->menuItem->price;
                $customizationPrice = $cart->customization_price ?? 0;
                return ($basePrice + $customizationPrice) * $cart->quantity;
            });
    }
}
