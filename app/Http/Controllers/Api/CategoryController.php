<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

/**
 * @group Categories
 * 
 * APIs for browsing product categories
 */
class CategoryController extends Controller
{
    /**
     * List categories
     * 
     * Get a list of all product categories ordered by display order and name.
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Audio",
     *       "slug": "audio",
     *       "description": "Audio files, music tracks, and sound effects",
     *       "icon": "musical-note",
     *       "order": 1
     *     },
     *     {
     *       "id": 2,
     *       "name": "Video",
     *       "slug": "video",
     *       "description": "Video templates, stock footage, and motion graphics",
     *       "icon": "film",
     *       "order": 2
     *     },
     *     {
     *       "id": 3,
     *       "name": "3D Models",
     *       "slug": "3d-models",
     *       "description": "3D models, textures, and assets",
     *       "icon": "cube",
     *       "order": 3
     *     }
     *   ]
     * }
     * 
     * @response 429 {
     *   "message": "Too Many Requests"
     * }
     */
    public function index()
    {
        $categories = Category::orderBy('order')
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }
}
