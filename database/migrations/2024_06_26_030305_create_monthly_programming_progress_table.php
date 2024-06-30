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
        Schema::create('monthly_programming_progress', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_order');
            $table->foreignId('line_id')->constrained();
            $table->uuid('shift_id');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
            // Columns from BaseModel
            $table->timestamp('created_at')->nullable()->default(now());
            $table->timestamp('updated_at')->nullable()->default(now());
            $table->softDeletes();
            $table->integer('version')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mounthly_programming_progress');
    }
};