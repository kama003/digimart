<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group User Purchases
 * 
 * APIs for managing user purchases and downloads (requires authentication)
 */
class UserController extends Controller
{
    public function __construct(
        private StorageService $storageService
    ) {}

    /**
     * Get purchased products
     * 
     * Retrieve a paginated list of products purchased by the authenticated user.
     * 
     * @authenticated
     * 
     * @queryParam page integer Page number for pagination. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Premium Audio Pack",
     *       "slug": "premium-audio-pack",
     *       "description": "High-quality audio files for your projects",
     *       "short_description": "Professional audio collection",
     *       "product_type": "audio",
     *       "price": "49.99",
     *       "thumbnail_path": "products/thumbnails/audio-pack.jpg",
     *       "category": {
     *         "id": 1,
     *         "name": "Audio",
     *         "slug": "audio"
     *       },
     *       "seller": {
     *         "id": 2,
     *         "name": "John Doe"
     *       },
     *       "purchased_at": "2024-01-20T14:30:00+00:00",
     *       "transaction_id": 5
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "last_page": 3,
     *     "per_page": 20,
     *     "total": 45
     *   }
     * }
     * 
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     * 
     * @response 429 {
     *   "message": "Too Many Requests"
     * }
     */
    public function purchases(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $transactions = $user->transactions()
            ->with(['transactionItems.product.category', 'transactionItems.product.user'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);
        
        $purchases = $transactions->getCollection()->flatMap(function ($transaction) {
            return $transaction->transactionItems->map(function ($item) use ($transaction) {
                return [
                    'id' => $item->product->id,
                    'title' => $item->product->title,
                    'slug' => $item->product->slug,
                    'description' => $item->product->description,
                    'short_description' => $item->product->short_description,
                    'product_type' => $item->product->product_type,
                    'price' => $item->price,
                    'thumbnail_path' => $item->product->thumbnail_path,
                    'category' => [
                        'id' => $item->product->category->id,
                        'name' => $item->product->category->name,
                        'slug' => $item->product->category->slug,
                    ],
                    'seller' => [
                        'id' => $item->product->user->id,
                        'name' => $item->product->user->name,
                    ],
                    'purchased_at' => $transaction->created_at->toIso8601String(),
                    'transaction_id' => $transaction->id,
                ];
            });
        });
        
        return response()->json([
            'success' => true,
            'data' => $purchases,
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }

    /**
     * Generate download link
     * 
     * Generate a new time-limited download link for a purchased product. 
     * If the existing link has expired, a new one will be created.
     * 
     * @authenticated
     * 
     * @urlParam download integer required The download ID. Example: 123
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "download_url": "https://s3.amazonaws.com/bucket/products/file.zip?signature=...",
     *     "expires_at": "2024-01-21T14:30:00+00:00",
     *     "product": {
     *       "id": 1,
     *       "title": "Premium Audio Pack",
     *       "slug": "premium-audio-pack"
     *     }
     *   },
     *   "message": "Download link generated successfully."
     * }
     * 
     * @response 403 {
     *   "success": false,
     *   "message": "Unauthorized access to this download."
     * }
     * 
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\Download]."
     * }
     * 
     * @response 429 {
     *   "message": "Too Many Requests"
     * }
     */
    public function generateDownload(Request $request, Download $download): JsonResponse
    {
        $user = $request->user();
        
        // Verify the download belongs to the authenticated user
        if ($download->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this download.',
            ], 403);
        }
        
        // Check if the download link has expired
        if ($download->expires_at->isPast()) {
            // Generate a new download link
            $expiryHours = config('app.download_link_expiry_hours', 24);
            $download->download_url = $this->storageService->generateTemporaryUrl(
                $download->product->file_path,
                $expiryHours
            );
            $download->expires_at = now()->addHours($expiryHours);
            $download->save();
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'download_url' => $download->download_url,
                'expires_at' => $download->expires_at->toIso8601String(),
                'product' => [
                    'id' => $download->product->id,
                    'title' => $download->product->title,
                    'slug' => $download->product->slug,
                ],
            ],
            'message' => 'Download link generated successfully.',
        ]);
    }
}
