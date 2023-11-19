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
        Schema::create('developments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->boolean('is_start');
            $table->boolean('is_complete');
            $table->integer('default_time');
            $table->integer('remaining_time');
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->text('selected_id_list');
            $table->timestamps();

            $table->foreignUlid('project_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developments');
    }
};