<div>

    <hr>

    <h3 style="font-size: 1.3rem; font-weight: 700; margin: 1.5rem 0 1rem 0; color: #fff; letter-spacing: 0.5px;">
        Order List
    </h3>
    <ul>
        @foreach ($orders as $order)
            <li
                style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; margin-bottom: 0.5rem; background: #23272b; border-radius: 6px;">
                <div style="display: flex; align-items: center;">
                    @if ($order->image)
                        <img src="{{ asset('storage/' . $order->image) }}" alt="{{ $order->order_method }}"
                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 0.75rem;">
                    @endif
                    <span style="font-weight: 600; color: #fff;">{{ ucwords($order->order_method) }}</span>
                    <span style="color: #9ca3af; margin-left: 0.75rem; font-size: 0.95em;">
                        <span
                            style="display: inline-block; padding: 0.15em 0.7em; border-radius: 12px; font-size: 0.85em; font-weight: 600; background: {{ $order->is_active ? '#22c55e' : '#6b7280' }}; color: #fff;">
                            {{ $order->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
                <div>
                    <button wire:click="edit({{ $order->id }})"
                        style="background: #3b82f6; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer; margin-right: 0.5rem;">
                        Edit
                    </button>
                    <button wire:click="delete({{ $order->id }})"
                        style="background: #ef4444; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        Delete
                    </button>
                </div>
            </li>
        @endforeach
    </ul>
</div>
