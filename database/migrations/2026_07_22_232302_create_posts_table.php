<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shared_post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->text('body')->nullable();
            $table->string('image_path')->nullable();
            $table->string('visibility')->default('public');
            $table->timestamps();

            $table->index(['visibility', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};