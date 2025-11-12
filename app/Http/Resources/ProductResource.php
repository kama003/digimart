<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'product_type' => $this->product_type,
            'price' => $this->price,
            'license_type' => $this->license_type,
            'thumbnail_url' => $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null,
            'file_size' => $this->file_size,
            'downloads_count' => $this->downloads_count,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'seller' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
