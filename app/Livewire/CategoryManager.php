<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryManager extends Component
{
    public $categories;
    public $name;
    public $editingId = null;

    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:categories,name,' . $this->editingId,
            'description' => 'required',
            'image' => 'nullable|image|mimes:png,jpeg|max:2048',
            'is_active' => '',
        ];
    }

    public function mount()
    {
        $this->loadCategories();
    }

    public function updated($property)
    {
        $this->validateOnly($property); // ✅ Real-time validation
    }

    public function save()
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        $this->resetInput();
        $this->loadCategories();
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
    }

    public function resetInput()
    {
        $this->name = '';
        $this->editingId = null;
    }

    public function loadCategories()
    {
        $this->categories = Category::latest()->get();
    }

    public function render()
    {
        return view('livewire.category-manager');
    }
}
