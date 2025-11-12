<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->enum('product_type', ['audio', 'video', '3d', 'template', 'graphic']);
            $table->decimal('price', 10, 2);
            $table->string('license_type');
            $table->string('thumbnail_path');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('rejection_reason')->nullable();
            $table->integer('downloads_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('category_id');
            $table->index('slug');
            $table->index(['is_approved', 'is_active']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
