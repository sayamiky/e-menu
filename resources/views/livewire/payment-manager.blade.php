<div>
    {{-- <h2>{{ $editingId ? 'Edit' : 'Add' }} Payment</h2> --}}

    <div x-data="{ open: {{ $editingId ? 'true' : 'false' }} }" class="accordion" style="margin-bottom: 2rem;">
        <button @click="open = !open" type="button"
            style="width: 100%; text-align: left; background: #23272b; color: #fff; border: none; padding: 1rem 1.5rem; border-radius: 8px 8px 0 0; font-size: 1.1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
            <span>{{ $editingId ? 'Edit' : 'Add New' }} Payment</span>
            <span x-text="open ? '▲' : '▼'" style="font-size: 1.2em;"></span>
        </button>
        <div x-show="open" x-transition
            style="background: #292d31; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 8px 8px; overflow: hidden;">
            <form wire:submit.prevent="save" style="max-width: 1000px; margin: 0 auto; padding: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <label for="paymentMethod" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Payment
                        Method</label>
                    <input id="paymentMethod" type="text" wire:model="paymentMethod" placeholder="Payment Method"
                        style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
                    @error('paymentMethod')
                        <span style="color:red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="image" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Image</label>
                    <input id="image" type="file" wire:model="image" style="width: 100%;">
                    @error('image')
                        <span style="color:red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="font-weight: 600;">
                        <input type="checkbox" wire:model="is_active" style="margin-right: 0.5rem;">
                        Active
                    </label>
                    @error('is_active')
                        <span style="color:red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit"
                        style="background: #2563eb; color: #fff; border: none; padding: 0.5rem 1.2rem; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        {{ $editingId ? 'Update' : 'Save' }}
                    </button>
                    @if ($editingId)
                        <button type="button" wire:click="resetInput"
                            style="background: #e5e7eb; color: #374151; border: none; padding: 0.5rem 1.2rem; border-radius: 4px; font-weight: 600; cursor: pointer;">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <hr>

    <h3 style="font-size: 1.3rem; font-weight: 700; margin: 1.5rem 0 1rem 0; color: #fff; letter-spacing: 0.5px;">
        Payment List
    </h3>
    <ul>
        @foreach ($payments as $payment)
            <li
                style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; margin-bottom: 0.5rem; background: #23272b; border-radius: 6px;">
                <div style="display: flex; align-items: center;">
                    @if ($payment->image)
                        <img src="{{ asset('storage/' . $payment->image) }}" alt="{{ $payment->payment_method }}"
                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 0.75rem;">
                    @endif
                    <span style="font-weight: 600; color: #fff;">{{ ucwords($payment->payment_method) }}</span>
                    <span style="color: #9ca3af; margin-left: 0.75rem; font-size: 0.95em;">
                        <span
                            style="display: inline-block; padding: 0.15em 0.7em; border-radius: 12px; font-size: 0.85em; font-weight: 600; background: {{ $payment->is_active ? '#22c55e' : '#6b7280' }}; color: #fff;">
                            {{ $payment->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
                <div>
                    <button wire:click="edit({{ $payment->id }})"
                        style="background: #3b82f6; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer; margin-right: 0.5rem;">
                        Edit
                    </button>
                    <button wire:click="delete({{ $payment->id }})"
                        style="background: #ef4444; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        Delete
                    </button>
                </div>
            </li>
        @endforeach
    </ul>
</div>
