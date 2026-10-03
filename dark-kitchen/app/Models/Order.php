<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'latitude',
        'longitude',
        'reference',
        'subtotal',
        'delivery_fee',
        'total',
        'payment_method',
        'cash_amount',
        'payment_status',
        'order_status',
        'payment_receipt',
        'notes',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}