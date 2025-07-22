<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'order_number',
        'menu_id',
        'quantity',
        'price',
        'subtotal',
        'note',
    ];

    /**
     * Get the order associated with the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_number', 'order_number');
    }

    /**
     * Get the menu associated with the order item.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
