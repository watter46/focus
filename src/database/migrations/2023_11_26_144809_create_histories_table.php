<?php declare(strict_types=1);

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
        Schema::create('histories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('project_name');
            $table->string('label', 20);
            $table->timestamp('started_at');
            $table->timestamp('finished_at');
            $table->integer('elapsed_time');
            $table->text('completed_task_list');
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
