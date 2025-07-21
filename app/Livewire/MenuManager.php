<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Menu;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class MenuManager extends Component
{
    use WithFileUploads;

    public $menus;
    public $categories;
    public $name;
    public $description;
    public $image;
    public $categoryId;
    public $price;
    public $is_active = true;
    public $editingId = null;

    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:categories,name,' . $this->editingId,
            'description' => 'required',
            'image' => 'nullable' . ($this->editingId ? '' : '|image|mimes:png,jpeg|max:2048'),
            'categoryId' => 'required|numeric|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'is_active' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->loadMenus();
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
            $imagePath = is_string($this->image) && $this->editingId ? $this->image : $this->image->store('menus', 'public');
        }

        Menu::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'image' => $imagePath,
                'category_id' => $this->categoryId,
                'price' => $this->price,
                'is_active' => $this->is_active,
            ]
        );

        $this->resetInput();
        $this->loadMenus();
    }

    public function edit($id)
    {
        $menu = Menu::find($id);
        $this->editingId = $menu->id;
        $this->name = $menu->name;
        $this->description = $menu->description;
        $this->categoryId = $menu->category_id;
        $this->price = $menu->price;
        $this->is_active = (bool) $menu->is_active;
        $this->image = $menu->image ?? null;
    }

    public function resetInput()
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->categoryId = null;
        $this->price = 0;
        $this->image = null;
        $this->is_active = true;
    }

    public function delete($id)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $menu->delete();
            $this->loadMenus();
        }
    }

    public function loadMenus()
    {
        $this->menus = Menu::latest()->get();
    }

    public function loadCategories() {
        $this->categories = Category::active()->get(); // Assuming you have a method to load categories
    }

    public function render()
    {
        return view('livewire.menu-manager');
    }
}
