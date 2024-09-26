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
        Schema::create('service_accounts', function (Blueprint $table) {
            $table->id();
            $table->json('credentials');
            $table->text('checksum');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('service_account_sites', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_account_id');
            $table->bigInteger('site_id');
            $table->timestamps();
        });

        Schema::create('service_account_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_account_id');
            $table->string('description');
            $table->nullableMorphs('model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_accounts');
    }
};
