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
        Schema::create('readables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('readable_id');
            $table->string('readable_type');
            $table->string('role');
            $table->boolean('checked')->default(false);
            $table->index(['readable_id', 'readable_type']);
            $table->index('role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readables');
    }
};
