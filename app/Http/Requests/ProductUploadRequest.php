<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isSeller() || auth()->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'product_type' => ['required', 'in:audio,video,3d,template,graphic'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'license_type' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB
            'file' => ['required', 'file', 'max:512000'], // 500MB
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your product.',
            'description.required' => 'Please provide a detailed description.',
            'description.min' => 'Description must be at least 50 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'product_type.required' => 'Please select a product type.',
            'product_type.in' => 'Invalid product type selected.',
            'price.required' => 'Please set a price for your product.',
            'price.min' => 'Price must be at least $0.01.',
            'price.max' => 'Price cannot exceed $99,999.99.',
            'license_type.required' => 'Please specify the license type.',
            'thumbnail.required' => 'Please upload a thumbnail image.',
            'thumbnail.image' => 'Thumbnail must be an image file.',
            'thumbnail.mimes' => 'Thumbnail must be a JPEG, JPG, PNG, or WebP file.',
            'thumbnail.max' => 'Thumbnail size cannot exceed 5MB.',
            'file.required' => 'Please upload the product file.',
            'file.max' => 'File size cannot exceed 500MB.',
        ];
    }
}
