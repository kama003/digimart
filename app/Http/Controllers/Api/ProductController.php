<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @group Products
 * 
 * APIs for browsing and searching digital products
 */
class ProductController extends Controller
{
    /**
     * List products
     * 
     * Get a paginated list of approved and active products with optional filters.
     * 
     * @queryParam category string Filter by category slug. Example: audio
     * @queryParam type string Filter by product type (audio, video, 3d, template, graphic). Example: audio
     * @queryParam min_price number Filter by minimum price. Example: 10.00
     * @queryParam max_price number Filter by maximum price. Example: 100.00
     * @queryParam search string Search in title, description, and short description. Example: music
     * @queryParam page integer Page number for pagination. Example: 1
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Premium Audio Pack",
     *       "slug": "premium-audio-pack",
     *       "description": "High-quality audio files for your projects",
     *       "short_description": "Professional audio collection",
     *       "product_type": "audio",
     *       "price": "49.99",
     *       "license_type": "Commercial",
     *       "thumbnail_path": "products/thumbnails/audio-pack.jpg",
     *       "downloads_count": 150,
     *       "category": {
     *         "id": 1,
     *         "name": "Audio",
     *         "slug": "audio"
     *       },
     *       "seller": {
     *         "id": 2,
     *         "name": "John Doe"
     *       },
     *       "created_at": "2024-01-15T10:30:00.000000Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "http://localhost/api/v1/products?page=1",
     *     "last": "http://localhost/api/v1/products?page=5",
     *     "prev": null,
     *     "next": "http://localhost/api/v1/products?page=2"
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 5,
     *     "per_page": 20,
     *     "to": 20,
     *     "total": 95
     *   }
     * }
     * 
     * @response 429 {
     *   "message": "Too Many Requests"
     * }
     */
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['category', 'user'])
            ->where('is_approved', true)
            ->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by product type
        if ($request->filled('type')) {
            $query->where('product_type', $request->type);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        // Paginate results
        $products = $query->paginate(20);

        return ProductResource::collection($products);
    }

    /**
     * Get product details
     * 
     * Retrieve detailed information about a specific product by its slug.
     * 
     * @urlParam slug string required The product slug. Example: premium-audio-pack
     * 
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Premium Audio Pack",
     *     "slug": "premium-audio-pack",
     *     "description": "High-quality audio files for your projects. Includes 50+ tracks in various genres.",
     *     "short_description": "Professional audio collection",
     *     "product_type": "audio",
     *     "price": "49.99",
     *     "license_type": "Commercial",
     *     "thumbnail_path": "products/thumbnails/audio-pack.jpg",
     *     "downloads_count": 150,
     *     "category": {
     *       "id": 1,
     *       "name": "Audio",
     *       "slug": "audio"
     *     },
     *     "seller": {
     *       "id": 2,
     *       "name": "John Doe"
     *     },
     *     "created_at": "2024-01-15T10:30:00.000000Z"
     *   }
     * }
     * 
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\Product]."
     * }
     */
    public function show(string $slug)
    {
        $product = Product::with(['category', 'user'])
            ->where('slug', $slug)
            ->where('is_approved', true)
            ->where('is_active', true)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
