<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'order_number',
        'name',
        'email',
        'phone',
    ];

    /**
     * Get the orders associated with the customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_number', 'order_number');
    }
}
