# Product Preview System

## Overview

The preview system allows sellers to upload limited preview versions of their products that customers can view before purchasing. This helps increase sales by giving customers confidence in the product quality while protecting the seller's full content.

## Features

### 1. Preview File Upload
- Sellers can optionally upload a preview file when creating or editing products
- Preview files are stored publicly (accessible without authentication)
- Maximum preview file size: 50MB
- Main product files remain private and require purchase to access

### 2. Download Protection

#### Audio & Video Files
- `controlsList="nodownload"` attribute prevents browser download button
- `oncontextmenu="return false;"` disables right-click context menu
- Customers can play but cannot easily download the preview

#### Images & Graphics
- `oncontextmenu="return false;"` disables right-click save
- `user-select: none` prevents text/image selection
- Overlay div prevents direct interaction

### 3. Best Practices for Sellers

#### Audio Files
- Upload a 30-60 second clip of the full track
- Apply watermark audio (voice-over saying "preview" or "sample")
- Reduce bitrate to 128kbps or lower
- Add fade-in/fade-out effects

#### Video Files
- Upload 30-90 second clips
- Add watermark overlay with your brand/logo
- Reduce resolution (720p instead of 4K)
- Lower bitrate for smaller file size

#### Images & Graphics
- Reduce resolution (e.g., 1200px width instead of 6000px)
- Add visible watermark across the image
- Save with lower quality settings
- Consider adding grid overlay or blur effect

#### Templates & 3D Models
- Show sample pages or screenshots
- Include watermarks on preview images
- Provide low-poly versions for 3D models
- Show only partial functionality

## Technical Implementation

### Database Schema

```sql
ALTER TABLE products ADD COLUMN preview_path VARCHAR(255) NULL AFTER file_path;
```

### File Storage Structure

```
storage/
├── app/
│   ├── public/
│   │   ├── products/
│   │   │   ├── thumbnails/     # Public thumbnails
│   │   │   └── previews/       # Public preview files
│   └── private/
│       └── products/
│           └── files/           # Private full product files
```

### Security Measures

1. **Main Product Files**: Stored in private disk, only accessible after purchase verification
2. **Preview Files**: Stored in public disk but should be watermarked/limited by seller
3. **HTML Protection**: Browser-level download prevention (not foolproof but deters casual users)
4. **Access Control**: Full files require authentication and purchase verification

### Routes

- Product detail page: `/product/{slug}` - Shows preview if available
- Download full file: `/download/{download}` - Requires authentication and purchase

### Components

#### ProductUpload Component
- Handles preview file upload during product creation
- Validates file size (max 50MB)
- Stores preview in public disk

#### ProductEdit Component
- Allows updating preview file
- Deletes old preview when new one is uploaded
- Maintains existing preview if not updated

#### ProductDetail View
- Displays preview with download protection
- Shows appropriate player/viewer based on product type
- Displays "Limited Preview" badge
- Shows message encouraging purchase for full quality

## Limitations

### Browser-Level Protection
The download protection implemented is **not foolproof**. Determined users can still:
- Use browser developer tools to access media URLs
- Use screen recording software
- Use third-party download tools

### Why This Approach?
- Balances user experience with protection
- Deters casual downloading
- Encourages legitimate purchases
- Industry-standard approach (used by stock media sites)

### Additional Protection Options

For stronger protection, consider:
1. **Server-side streaming**: Stream chunks instead of direct file access
2. **DRM (Digital Rights Management)**: Enterprise-level protection
3. **Time-limited URLs**: Generate expiring signed URLs
4. **HLS/DASH streaming**: Encrypted streaming protocols
5. **Watermarking service**: Automated watermark application

## Usage Examples

### Creating Product with Preview

```php
// In ProductUpload component
$product = Product::create([
    'title' => 'Amazing Audio Track',
    'file_path' => 'products/files/full-track.mp3',      // Private
    'preview_path' => 'products/previews/preview.mp3',   // Public
    // ... other fields
]);
```

### Displaying Preview

```blade
@if($product->preview_path)
    <audio controls controlsList="nodownload" oncontextmenu="return false;">
        <source src="{{ Storage::url($product->preview_path) }}" type="audio/mpeg">
    </audio>
@endif
```

### Checking Purchase Before Download

```php
// In DownloadController
public function download(Download $download)
{
    // Verify user owns this download
    if ($download->user_id !== auth()->id()) {
        abort(403);
    }
    
    // Return private file
    return Storage::disk('private')->download($download->product->file_path);
}
```

## Future Enhancements

1. **Automatic Preview Generation**
   - Auto-generate previews from full files
   - Apply watermarks automatically
   - Trim duration automatically

2. **Preview Analytics**
   - Track preview plays
   - Measure conversion rates
   - A/B test preview strategies

3. **Advanced Protection**
   - Implement HLS streaming
   - Add forensic watermarking
   - Use CDN with token authentication

4. **Preview Templates**
   - Provide watermark templates
   - Offer preview editing tools
   - Suggest optimal preview settings

## Conclusion

The preview system strikes a balance between showcasing products and protecting intellectual property. While not impenetrable, it provides reasonable protection while maintaining a good user experience. Sellers should always create quality previews that demonstrate value without giving away the full product.
