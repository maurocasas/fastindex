<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sitemaps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site_id');
            $table->text('url');
            $table->timestamp('last_download_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('pending')->default(true);
            $table->bigInteger('submitted')->default(0);
            $table->bigInteger('indexed')->default(0);
            $table->bigInteger('warnings')->default(0);
            $table->bigInteger('errors')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitemaps');
    }
};
