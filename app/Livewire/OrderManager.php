<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderManager extends Component
{
    public $orders;

    public function mount()
    {
        $this->loadOrders();
        // $this->loadItems();
        // $this->loadCustomers();
        // $this->loadPayments();
    }

    public function loadOrders()
    {
        $this->orders = Order::latest()->get();
    }

    public function render()
    {
        return view('livewire.order-manager');
    }
}
