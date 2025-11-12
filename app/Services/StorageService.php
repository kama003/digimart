<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    /**
     * Upload a file to the configured storage disk
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $visibility 'public' or 'private'
     * @return string The path where the file was stored
     */
    public function uploadFile(UploadedFile $file, string $path, string $visibility = 'private'): string
    {
        try {
            $disk = $visibility === 'public' ? 'public' : config('filesystems.default');
            
            $storedPath = Storage::disk($disk)->putFile($path, $file, $visibility);

            Log::info('File uploaded successfully', [
                'path' => $storedPath,
                'disk' => $disk,
                'visibility' => $visibility,
                'size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ]);

            return $storedPath;
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'path' => $path,
                'visibility' => $visibility,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a file from storage
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        try {
            // Try to delete from both disks to ensure cleanup
            $deleted = false;
            
            if (Storage::disk('public')->exists($path)) {
                $deleted = Storage::disk('public')->delete($path);
            }
            
            if (Storage::disk(config('filesystems.default'))->exists($path)) {
                $deleted = Storage::disk(config('filesystems.default'))->delete($path) || $deleted;
            }
            
            if ($deleted) {
                Log::info('File deleted successfully', ['path' => $path]);
            }
            
            return $deleted;
        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Generate a temporary URL for downloading a file
     *
     * @param string $path
     * @param int|null $hours Number of hours until expiration (defaults to DOWNLOAD_LINK_EXPIRY_HOURS from .env)
     * @return string
     */
    public function generateTemporaryUrl(string $path, ?int $hours = null): string
    {
        try {
            $hours = $hours ?? config('filesystems.download_link_expiry_hours', 24);
            $expiration = now()->addHours($hours);

            $url = Storage::disk(config('filesystems.default'))
                ->temporaryUrl($path, $expiration);

            Log::info('Temporary URL generated', [
                'path' => $path,
                'expiration_hours' => $hours,
            ]);

            return $url;
        } catch (\Exception $e) {
            Log::error('Failed to generate temporary URL', [
                'path' => $path,
                'hours' => $hours,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the size of a file in bytes
     *
     * @param string $path
     * @return int
     */
    public function getFileSize(string $path): int
    {
        try {
            return Storage::disk(config('filesystems.default'))->size($path);
        } catch (\Exception $e) {
            Log::error('Failed to get file size', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
