<div>
    <h2>{{ $editingId ? 'Edit' : 'Add' }} Category</h2>

    <form wire:submit.prevent="save">
        <input type="text" wire:model="name" placeholder="Category Name">
        @error('name')
            <span style="color:red;">{{ $message }}</span>
        @enderror

        <textarea wire:model="description" placeholder="Description"></textarea>
        @error('description')
            <span style="color:red;">{{ $message }}</span>
        @enderror

        <input type="file" wire:model="image">
        @error('image')
            <span style="color:red;">{{ $message }}</span>
        @enderror

        <label>
            <input type="checkbox" wire:model="is_active">
            Active
        </label>
        @error('is_active')
            <span style="color:red;">{{ $message }}</span>
        @enderror

        <button type="submit">{{ $editingId ? 'Update' : 'Save' }}</button>
        @if ($editingId)
            <button type="button" wire:click="resetInput">Cancel</button>
        @endif
    </form>

    <hr>

    <h3>Category List</h3>
    <ul>
        @foreach ($categories as $category)
            <li>
                {{ $category->name }}
                <button wire:click="edit({{ $category->id }})">Edit</button>
            </li>
        @endforeach
    </ul>
</div>
