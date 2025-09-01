<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // This will be our equivalent of ObjectId
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('completed')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Creates created_at and updated_at columns
            
            // Add indexes for better performance
            $table->index('user_id');
            $table->index('completed');
            $table->index(['user_id', 'completed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};