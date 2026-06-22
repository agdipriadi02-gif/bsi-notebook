<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->string('file_type')->default('pdf'); // pdf, link, note
            $table->string('file_size')->nullable();
            $table->enum('status', ['processing', 'indexed', 'error'])->default('processing');
            $table->longText('summary')->nullable();
            $table->longText('content_text')->nullable();
            $table->string('cover_color')->default('#4CAF50');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
