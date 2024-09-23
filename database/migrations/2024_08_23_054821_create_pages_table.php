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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id');
            $table->bigInteger('sitemap_id');
            $table->text('url');
            $table->text('path');
            $table->string('coverage_state')->nullable();
            $table->string('indexing_state')->nullable();
            $table->timestamp('crawled_at')->nullable();
            $table->timestamp('queried_at')->nullable();
            $table->timestamp('indexed_at')->nullable();
            $table->boolean('busy')->default(false);
            $table->boolean('not_found')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
