<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class CategoryManager extends Component
{
    use WithFileUploads;

    public $categories;
    public $name;
    public $description;
    public $image;
    public $is_active = true;
    public $editingId = null;

    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:categories,name,' . $this->editingId,
            'description' => 'required',
            'image' => 'nullable' . ($this->editingId ? '' : '|image|mimes:png,jpeg|max:2048'),
            'is_active' => 'boolean',
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

        // In save() method:
        $slug = Str::slug($this->name);

        $imagePath = null;
        if ($this->image) {
            $imagePath = is_string($this->image) && $this->editingId ? $this->image : $this->image->store('categories', 'public');
        }

        Category::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'image' => $imagePath,
                'is_active' => $this->is_active,
            ]
        );

        $this->resetInput();
        $this->loadCategories();
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = (bool) $category->is_active;
        $this->image = $category->image ?? null;
    }

    public function resetInput()
    {
        $this->name = '';
        $this->description = '';
        $this->editingId = null;
        $this->image = null;
        $this->is_active = true;
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            $this->loadCategories();
        }
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
