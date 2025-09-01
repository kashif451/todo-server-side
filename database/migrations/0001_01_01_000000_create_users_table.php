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
    Schema::create('users', function (Blueprint $table) {
        $table->id(); // acts as _id
        $table->string('email')->unique(); // unique & required
        $table->string('password'); // hashed password
        $table->string('name'); // required
        $table->timestamps(); // created_at & updated_at
    });
}

public function down(): void
{
    Schema::dropIfExists('users');
}

};
