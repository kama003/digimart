<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManagement extends Component
{
    use WithPagination;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $icon = '';
    public $order = 0;
    public $editingId = null;
    public $showModal = false;
    public $deleteConfirmId = null;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:10',
            'order' => 'nullable|integer|min:0',
        ];

        // If editing, exclude current category from unique check
        if ($this->editingId) {
            $rules['slug'] = 'required|string|max:255|unique:categories,slug,' . $this->editingId;
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Category name is required.',
        'name.max' => 'Category name must not exceed 255 characters.',
        'slug.required' => 'Slug is required.',
        'slug.unique' => 'This slug is already in use.',
        'description.max' => 'Description must not exceed 1000 characters.',
        'icon.max' => 'Icon must not exceed 10 characters.',
        'order.integer' => 'Order must be a number.',
        'order.min' => 'Order must be at least 0.',
    ];

    /**
     * Auto-generate slug from name
     */
    public function updatedName($value)
    {
        if (!$this->editingId || empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    /**
     * Open modal for creating new category
     */
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    /**
     * Open modal for editing category
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->order = $category->order ?? 0;
        $this->showModal = true;
    }

    /**
     * Save category (create or update)
     */
    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'icon' => $this->icon,
                'order' => $this->order ?? 0,
            ];

            if ($this->editingId) {
                $category = Category::findOrFail($this->editingId);
                $category->update($data);
                
                session()->flash('success', 'Category updated successfully.');
                
                \Illuminate\Support\Facades\Log::info('Category updated', [
                    'category_id' => $category->id,
                    'name' => $this->name,
                    'admin_id' => auth()->id(),
                ]);
            } else {
                $category = Category::create($data);
                
                session()->flash('success', 'Category created successfully.');
                
                \Illuminate\Support\Facades\Log::info('Category created', [
                    'category_id' => $category->id,
                    'name' => $this->name,
                    'admin_id' => auth()->id(),
                ]);
            }

            // Clear cache
            cache()->forget('homepage.categories');
            cache()->forget('search.categories');

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Category save failed', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
            ]);

            session()->flash('error', 'Failed to save category. Please try again.');
        }
    }

    /**
     * Confirm deletion
     */
    public function confirmDelete($id)
    {
        $this->deleteConfirmId = $id;
    }

    /**
     * Delete category
     */
    public function delete()
    {
        if (!$this->deleteConfirmId) {
            return;
        }

        try {
            $category = Category::findOrFail($this->deleteConfirmId);
            
            // Check if category has products
            if ($category->products()->count() > 0) {
                session()->flash('error', 'Cannot delete category with existing products. Please reassign or delete products first.');
                $this->deleteConfirmId = null;
                return;
            }

            $categoryName = $category->name;
            $category->delete();

            session()->flash('success', "Category '{$categoryName}' deleted successfully.");
            
            \Illuminate\Support\Facades\Log::info('Category deleted', [
                'category_id' => $this->deleteConfirmId,
                'name' => $categoryName,
                'admin_id' => auth()->id(),
            ]);

            // Clear cache
            cache()->forget('homepage.categories');
            cache()->forget('search.categories');

            $this->deleteConfirmId = null;
            $this->resetPage();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Category deletion failed', [
                'error' => $e->getMessage(),
                'category_id' => $this->deleteConfirmId,
                'admin_id' => auth()->id(),
            ]);

            session()->flash('error', 'Failed to delete category. Please try again.');
            $this->deleteConfirmId = null;
        }
    }

    /**
     * Cancel deletion
     */
    public function cancelDelete()
    {
        $this->deleteConfirmId = null;
    }

    /**
     * Close modal and reset form
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->icon = '';
        $this->order = 0;
        $this->resetValidation();
    }

    /**
     * Render component
     */
    public function render()
    {
        $categories = Category::withCount('products')
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.category-management', [
            'categories' => $categories,
        ]);
    }
}
