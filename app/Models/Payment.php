<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'payment_method',
        'image',
        'is_active',
    ];

    /**
     * Get the orders associated with the payment method.
     */
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
