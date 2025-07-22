<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_date',
        'order_number',
        'payment_id',
        'order_status',
        'payment_status',
        'paid_at',
        'total_amount',
    ];

    /**
     * Get the payment method associated with the order.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the items associated with the order.
     */
    public function item()
    {
        return $this->hasMany(OrderItem::class, 'order_number', 'order_number');
    }

    /**
     * Get the customer associated with the order.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'order_number', 'order_number');
    }
}
