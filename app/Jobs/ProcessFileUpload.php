<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcessFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $productId,
        public string $thumbnailPath
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $product = Product::findOrFail($this->productId);

            // Check if thumbnail exists
            if (!Storage::exists($this->thumbnailPath)) {
                Log::warning('Thumbnail not found for processing', [
                    'product_id' => $this->productId,
                    'thumbnail_path' => $this->thumbnailPath,
                ]);
                return;
            }

            // Get the thumbnail file
            $thumbnailContent = Storage::get($this->thumbnailPath);

            // Create optimized versions (if using Intervention Image)
            // Note: This requires intervention/image package
            // Uncomment if you want to use this feature:
            
            /*
            $image = Image::make($thumbnailContent);
            
            // Create thumbnail (300x300)
            $thumbnail = $image->fit(300, 300);
            $thumbnailPath = 'thumbnails/thumb-' . basename($this->thumbnailPath);
            Storage::put($thumbnailPath, $thumbnail->encode());
            
            // Create medium size (600x600)
            $medium = $image->fit(600, 600);
            $mediumPath = 'thumbnails/medium-' . basename($this->thumbnailPath);
            Storage::put($mediumPath, $medium->encode());
            
            // Update product with optimized paths
            $product->update([
                'thumbnail_path' => $thumbnailPath,
                'medium_image_path' => $mediumPath,
            ]);
            */

            Log::info('File upload processed successfully', [
                'product_id' => $this->productId,
                'thumbnail_path' => $this->thumbnailPath,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('File upload processing failed', [
                'product_id' => $this->productId,
                'thumbnail_path' => $this->thumbnailPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toIso8601String(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('File upload processing job failed', [
            'product_id' => $this->productId,
            'thumbnail_path' => $this->thumbnailPath,
            'error' => $exception->getMessage(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

