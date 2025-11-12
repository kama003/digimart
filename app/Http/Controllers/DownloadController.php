<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Services\StorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{
    public function __construct(
        private StorageService $storageService
    ) {}

    /**
     * Handle the download request
     *
     * @param Download $download
     * @return RedirectResponse
     */
    public function download(Download $download): RedirectResponse
    {
        // Verify user owns the download
        if ($download->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to access this download.');
        }

        // Check if download link has expired
        if ($download->isExpired()) {
            $redirectRoute = \Illuminate\Support\Facades\Route::has('customer.purchases') 
                ? 'customer.purchases' 
                : 'dashboard';
            return redirect()->route($redirectRoute)
                ->with('error', 'This download link has expired. Please generate a new one.');
        }

        // Generate temporary URL using StorageService
        try {
            $temporaryUrl = $this->storageService->generateTemporaryUrl(
                $download->product->file_path
            );

            // Update downloaded_at timestamp
            $download->update([
                'downloaded_at' => now(),
            ]);

            // Redirect to the temporary URL
            return redirect()->away($temporaryUrl);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to generate download link', [
                'download_id' => $download->id,
                'user_id' => Auth::id(),
                'product_id' => $download->product_id,
                'file_path' => $download->product->file_path,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $redirectRoute = \Illuminate\Support\Facades\Route::has('customer.purchases') 
                ? 'customer.purchases' 
                : 'dashboard';
            return redirect()->route($redirectRoute)
                ->with('error', 'Unable to generate download link. Please try again or contact support.');
        }
    }
}
