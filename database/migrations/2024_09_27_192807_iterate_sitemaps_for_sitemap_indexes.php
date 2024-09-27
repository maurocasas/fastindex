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
        Schema::table('sitemaps', function (Blueprint $table) {
            $table->boolean('is_index')->default(false);
            $table->renameColumn('last_download_at', 'downloaded_at');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->bigInteger('sitemap_id')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sitemaps', function (Blueprint $table) {
            $table->dropColumn('is_index');
            $table->renameColumn('downloaded_at', 'last_download_at');
        });
    }
};
