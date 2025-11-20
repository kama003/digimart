<?php

namespace App\Livewire\Seller;

use App\Models\BlogPost;
use App\Models\Product;
use App\Models\User;
use App\Notifications\BlogPostSubmitted;
use App\Services\StorageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class BlogCreate extends Component
{
    use WithFileUploads;

    public $blogPostId = null;
    public $title = '';
    public $excerpt = '';
    public $content = '';
    public $featured_image;
    public $product_id = '';
    public $is_published = true;

    public function mount($id = null)
    {
        if ($id) {
            $post = BlogPost::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $this->blogPostId = $post->id;
            $this->title = $post->title;
            $this->excerpt = $post->excerpt;
            $this->content = $post->content;
            $this->product_id = $post->product_id;
            $this->is_published = $post->is_published;
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string|min:100',
            'featured_image' => $this->blogPostId ? 'nullable|image|max:5120' : 'required|image|max:5120',
            'product_id' => 'nullable|exists:products,id',
            'is_published' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'user_id' => Auth::id(),
                'title' => $this->title,
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'product_id' => $this->product_id ?: null,
                'is_published' => $this->is_published,
                'is_approved' => false,
            ];

            if ($this->featured_image && !is_string($this->featured_image)) {
                $storageService = app(StorageService::class);
                $data['featured_image'] = $storageService->uploadFile(
                    $this->featured_image,
                    'blog/images',
                    'public'
                );
            }

            if ($this->blogPostId) {
                $post = BlogPost::findOrFail($this->blogPostId);
                $post->update($data);
                session()->flash('success', 'Blog post updated and submitted for review.');
            } else {
                $post = BlogPost::create($data);
                session()->flash('success', 'Blog post created and submitted for review.');
            }

            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new BlogPostSubmitted($post));
            }

            return redirect()->route('seller.blog.index');
        } catch (\Exception $e) {
            \Log::error('Blog post save failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            session()->flash('error', 'Failed to save blog post. Please try again.');
        }
    }

    public function render()
    {
        $products = Product::where('user_id', Auth::id())
            ->where('is_approved', true)
            ->where('is_active', true)
            ->get();

        return view('livewire.seller.blog-create', [
            'products' => $products,
        ]);
    }
}
