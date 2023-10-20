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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->string('provider_name');
            $table->string('provider_id');
            $table->string('provider_token');
            $table->string('provider_refresh_token')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'provider_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
