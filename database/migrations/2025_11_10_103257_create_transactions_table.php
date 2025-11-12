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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('payment_gateway', ['stripe', 'paytm']);
            $table->string('payment_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->decimal('seller_amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('payment_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
