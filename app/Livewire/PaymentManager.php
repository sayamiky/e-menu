<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentManager extends Component
{
    use WithFileUploads;

    public $payments;
    public $paymentMethod;
    public $image;
    public $is_active = true;
    public $editingId = null;

    protected function rules()
    {
        return [
            'paymentMethod' => 'required|string|min:3|unique:payments,payment_method,' . $this->editingId,
            'image' => 'nullable' . ($this->editingId ? '' : '|image|mimes:png,jpeg|max:2048'),
            'is_active' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->loadPayments();
    }

    public function updated($property)
    {
        $this->validateOnly($property); // ✅ Real-time validation
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = is_string($this->image) && $this->editingId ? $this->image : $this->image->store('payments', 'public');
        }

        Payment::updateOrCreate(
            ['id' => $this->editingId],
            [
                'payment_method' => $this->paymentMethod,
                'image' => $imagePath,
                'is_active' => $this->is_active,
            ]
        );

        $this->resetInput();
        $this->loadPayments();
    }

    public function edit($id)
    {
        $payment = Payment::find($id);
        $this->editingId = $payment->id;
        $this->paymentMethod = $payment->payment_method;
        $this->is_active = (bool) $payment->is_active;
        $this->image = $category->image ?? null;
    }

    public function resetInput()
    {
        $this->paymentMethod = '';
        $this->editingId = null;
        $this->image = null;
        $this->is_active = true;
    }

    public function delete($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $payment->delete();
            $this->loadPayments();
        }
    }

    public function loadPayments()
    {
        $this->payments = Payment::latest()->get();
    }

    public function render()
    {
        return view('livewire.payment-manager');
    }
}
