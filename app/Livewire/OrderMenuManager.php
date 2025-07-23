<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Livewire\Component;

class OrderMenuManager extends Component
{
    /*
    category list
    menu list
    payment list

    */
    public $menus;
    public $categories;
    public $payments;
    public $orderItems = [];
    public $paymentId;
    public $customerName;
    public $customerPhone;
    public $customerEmail;
    public $selectedCategory = '';
    public $quantities = [];


    protected function rules()
    {
        return [
            'orderItems' => 'required|array|min:1',
            'orderItems.*.menu_id' => 'required|exists:menus,id',
            'orderItems.*.quantity' => 'required|integer|min:1',
            'orderItems.*.note' => 'nullable|string|max:255',
            'paymentId' => 'required|exists:payments,id',
            'customerName' => 'nullable|string|min:2',
            'customerPhone' => 'nullable|string|min:8',
            'customerEmail' => 'nullable|email|max:255',
        ];
    }

    public function mount()
    {
        $this->loadMenus();
        $this->loadCategories();
        $this->loadPayments();
        $this->selectedCategory
            ? Menu::where('category_id', $this->selectedCategory)->get()
            : Menu::all();
    }

    public function updated($property)
    {
        $this->validateOnly($property); // ✅ Real-time validation
    }

    public function save()
    {
        $this->validate();

        // Create Order
        $order = Order::create([
            'order_date' => now(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'payment_id' => $this->paymentId,
            'order_status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => collect($this->orderItems)->sum(function ($item) {
                $menu = $this->menus->firstWhere('id', $item['menu_id']);
                return $menu ? $menu->price * $item['quantity'] : 0;
            }),
        ]);
        if (!empty($this->customerName)) {
            $customer = $order->customer()->create([
                'order_number' => $order->order_number,
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'customer_email' => $this->customerEmail,
            ]);
        }

        // Attach order items
        foreach ($this->orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->order_number,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => $this->menus->firstWhere('id', $item['menu_id'])->price,
                'subtotal' => $this->menus->firstWhere('id', $item['menu_id'])->price * $item['quantity'],
                'note' => $item['note'] ?? '',
            ])->order()->associate($order)->save();
        }

        $this->resetInput();
    }

    public function loadMenus()
    {
        $this->menus = Menu::active()->get();
    }

    public function loadCategories()
    {
        $this->categories = Category::active()->get(); // Assuming you have a method to load categories
    }

    public function loadPayments()
    {
        $this->payments = Payment::active()->get(); // Assuming you have a Payment model
    }

    public function addToCart($menuId)
    {
        $qty = $this->quantities[$menuId] ?? 1;
        // Simpan ke session atau database sesuai logika kamu
        session()->flash('message', "Added $qty item(s) to cart!");
    }

    public function render()
    {
        return view('livewire.order-menu-manager')->layout('layouts.guest');
    }
}
